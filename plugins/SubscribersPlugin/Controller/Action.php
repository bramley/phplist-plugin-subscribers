<?php
/**
 * SubscribersPlugin for phplist.
 *
 * This file is a part of SubscribersPlugin.
 *
 * SubscribersPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * SubscribersPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author    Duncan Cameron
 * @copyright 2011-2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is the controller for the plugin providing the action methods.
 */
class SubscribersPlugin_Controller_Action extends CommonPlugin_Controller
{
    const PLUGIN = 'SubscribersPlugin';
    const TEMPLATE = '/../view/action.tpl.php';
    const IDENTIFIER = 'Action Subscribers';
    /*
     *  Private variables
     */
    private $dao;

    private function validateFile()
    {
        $error = '';
        $f = $this->model->file;

        if ($f['error'] != 0) {
            $errorText = array(
                0 => $this->i18n->get('upload_error_0'),
                1 => $this->i18n->get('upload_error_1'),
                2 => $this->i18n->get('upload_error_2'),
                3 => $this->i18n->get('upload_error_3'),
                4 => $this->i18n->get('upload_error_4'),
                6 => $this->i18n->get('upload_error_6'),
            );
            $error = $errorText[$f['error']];
        } elseif (!preg_match('/csv|text/', $f['type'])) {
            $error = $this->i18n->get('error_extension');
        } elseif ($f['size'] == 0) {
            $error = $this->i18n->get('error_empty', $f['name']);
        }

        return $error;
    }

    private function processUsers(array $users, $action, $listId)
    {
        switch ($action) {
            case SubscribersPlugin_Model_Action::ACTION_UNCONFIRM:
                $count = 0;

                foreach ($users as $email) {
                    if ($this->dao->unconfirmUser($email)) {
                        ++$count;
                        addUserHistory(
                            $email,
                            self::IDENTIFIER,
                            $this->i18n->get('history_unconfirmed')
                        );
                    }
                }
                $result = $this->i18n->get('result_unconfirmed', $count);
                break;
            case SubscribersPlugin_Model_Action::ACTION_BLACKLIST:
                $count = 0;

                foreach ($users as $email) {
                    addUserToBlackList($email, $this->i18n->get('history_blacklisted', self::IDENTIFIER));
                    ++$count;
                }
                $result = $this->i18n->get('result_blacklisted', $count);
                break;
            case SubscribersPlugin_Model_Action::ACTION_DELETE:
                $dao = $this->dao;
                $deletedCount = 0;
                array_walk(
                    $users,
                    function ($email, $index) use ($dao, &$deletedCount) {
                        if ($row = $dao->userByEmail($email)) {
                            deleteUser($row['id']);
                            ++$deletedCount;
                        }
                    }
                );
                $result = $this->i18n->get('result_deleted', $deletedCount);
                break;
            case SubscribersPlugin_Model_Action::ACTION_REMOVE:
                $listName = $this->dao->listName($listId);
                $count = 0;

                foreach ($users as $email) {
                    $this->dao->removeFromList($email, $listId);
                    ++$count;
                    addUserHistory(
                        $email,
                        self::IDENTIFIER,
                        $this->i18n->get('history_removed', $listName)
                    );
                }
                $result = $this->i18n->get('result_removed', $listName, $count);
                break;
        }

        $this->logEvent(sprintf('%s - %s', self::IDENTIFIER, $result));

        return $result;
    }

    private function loadUsersFromFile()
    {
        $emails = file($this->model->file['tmp_name'], FILE_SKIP_EMPTY_LINES);
        $emails = array_map('trim', $emails);

        return array_filter(
            $emails,
            function ($item) {
                return (strpos($item, '@') !== false);
            }
        );
    }

    private function dropDownList($enabled)
    {
        $lists = iterator_to_array($this->dao->listsForOwner(null));

        return CHtml::dropDownList(
            'listId', $this->model->listId, array_column($lists, 'name', 'id'), array('disabled' => !$enabled)
        );
    }

    private function radioButtonList($enabled)
    {
        return CHtml::radioButtonList(
            'update',
            $this->model->update,
            array(
                SubscribersPlugin_Model_Action::ACTION_UNCONFIRM => $this->i18n->get('Unconfirm'),
                SubscribersPlugin_Model_Action::ACTION_BLACKLIST => $this->i18n->get('Blacklist'),
                SubscribersPlugin_Model_Action::ACTION_DELETE => $this->i18n->get('Delete'),
                SubscribersPlugin_Model_Action::ACTION_REMOVE => $this->i18n->get('Remove from list'),
            ),
            array('separator' => '<br />', 'disabled' => !$enabled)
        );
    }

    protected function actionApply()
    {
        $this->model->setProperties($_SESSION[self::PLUGIN]);

        $result = $this->processUsers($this->model->users, $this->model->update, $this->model->listId);
        $_SESSION[self::PLUGIN]['result'] = $result;
        $redirect = new CommonPlugin_PageURL();
        header('Location: ' . $redirect);
        exit;
    }

    protected function actionSubmit()
    {
        $this->model->setProperties($_POST);
        $error = '';

        switch ($_POST['submit']) {
            case 'Upload':
                $error = $this->validateFile();

                if ($error == '') {
                    $users = $this->loadUsersFromFile();
                }
                break;
            case 'Match':
                if ($this->model->pattern == '') {
                    $error = $this->i18n->get('error_match_not_entered');
                    break;
                }
                $users = $this->dao->matchUsers(
                    $this->model->pattern,
                    $this->model->update == SubscribersPlugin_Model_Action::ACTION_REMOVE
                        ? $this->model->listId
                        : null
                );

                if (count($users) == 0) {
                    $error = $this->i18n->get('error_no_match', $this->model->pattern);
                    break;
                }
                break;
            default:
                $error = 'unrecognised submit ' . $_POST['submit'];
        }

        if ($error) {
            $_SESSION[self::PLUGIN]['error'] = $error;
            $redirect = new CommonPlugin_PageURL();
        } else {
            $_SESSION[self::PLUGIN]['users'] = $users;
            $redirect = new CommonPlugin_PageURL(null, array('action' => 'displayUsers'));
        }
        $_SESSION[self::PLUGIN]['update'] = $this->model->update;
        $_SESSION[self::PLUGIN]['listId'] = $this->model->listId;

        header('Location: ' . $redirect);
        exit;
    }

    protected function actionDisplayUsers()
    {
        $this->model->setProperties($_SESSION[self::PLUGIN]);

        $toolbar = new CommonPlugin_Toolbar($this);
        $toolbar->addHelpButton('help');
        $cancel = new CommonPlugin_PageLink(new CommonPlugin_PageURL(null), 'Cancel', array('class' => 'button'));
        $params = array(
            'toolbar' => $toolbar->display(),
            'updateList' => $this->radioButtonList(false),
            'listSelect' => $this->dropDownList(false),
            'userArea' => CHtml::textArea('users', implode("\n", $this->model->users),
                array('rows' => '20', 'cols' => '30', 'disabled' => 1)
            ),
            'formURL' => new CommonPlugin_PageURL(null, array('action' => 'apply')),
            'cancel' => $cancel,
            'panelTitle' => $this->i18n->get('Confirm action and subscribers'),
        );
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    protected function actionDefault()
    {
        $params = array();

        if (isset($_SESSION[self::PLUGIN]['result'])) {
            $params['result'] = $_SESSION[self::PLUGIN]['result'];
            unset($_SESSION[self::PLUGIN]['result']);
            $this->model->setProperties($_SESSION[self::PLUGIN]);
        } elseif (isset($_SESSION[self::PLUGIN]['error'])) {
            $params['error'] = $_SESSION[self::PLUGIN]['error'];
            unset($_SESSION[self::PLUGIN]['error']);
            $this->model->setProperties($_SESSION[self::PLUGIN]);
        } else {
            unset($_SESSION[self::PLUGIN]);
        }

        $toolbar = new CommonPlugin_Toolbar($this);
        $toolbar->addHelpButton('help');
        $params += array(
            'toolbar' => $toolbar->display(),
            'formURL' => new CommonPlugin_PageURL(null, array('action' => 'submit')),
            'updateList' => $this->radioButtonList(true),
            'listSelect' => $this->dropDownList(true),
            'panelTitle' => $this->i18n->get('Select action and subscribers'),
        );
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    public function __construct()
    {
        parent::__construct();
        $this->dao = new SubscribersPlugin_DAO_Action(new CommonPlugin_DB());
        $this->model = new SubscribersPlugin_Model_Action();
        $this->model->setProperties($_REQUEST);
    }
}
