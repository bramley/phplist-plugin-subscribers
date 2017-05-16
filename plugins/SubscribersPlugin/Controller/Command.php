<?php

namespace phpList\plugin\SubscribersPlugin\Controller;

use CHtml;
use phpList\plugin\Common\Controller;
use phpList\plugin\Common\DB;
use phpList\plugin\Common\PageLink;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\SubscribersPlugin\Command\Factory;
use phpList\plugin\SubscribersPlugin\DAO\Command as DAO;
use phpList\plugin\SubscribersPlugin\Model\Command as Model;

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
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is the controller for the plugin providing the action methods.
 */
class Command extends Controller
{
    const HTML_ENABLED = 0;
    const HTML_DISABLED = 1;

    const PLUGIN = 'SubscribersPlugin';
    const TEMPLATE = '/../view/command.tpl.php';
    const TEMPLATE_2 = '/../view/command_2.tpl.php';
    const IDENTIFIER = 'Subscriber Commands';
    const HELP = 'https://resources.phplist.com/plugin/subscribers?&#subscriber_commands';
    /*
     *  Private variables
     */
    private $dao;
    private $model;
    private $toolbar;

    /**
     * Saves variables into the session then redirects and exits.
     *
     * @param string $redirect the redirect location
     * @param array  $session  variables to be stored in the session
     */
    private function redirectExit($redirect, array $session = array())
    {
        $_SESSION[self::PLUGIN] = $session;
        header('Location: ' . $redirect);
        exit;
    }

    /**
     * Validates that a file has been successfully uploaded.
     *
     * @return string an error message, empty for success
     */
    private function validateFile()
    {
        $error = '';
        $f = $this->model->file;

        if ($f['error'] != 0) {
            $errorText = array(
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

    /**
     * Applies the command to the set of subscribers.
     *
     * @param array $users     email addresses
     * @param bool  $commandId The command to be applied
     * @param bool  $listId    List id
     *
     * @return string a message summarising the command and number of affected subscribers
     */
    private function processUsers(array $users, $commandId, $listId)
    {
        $command = Factory::createCommand($commandId, $listId, $this->dao, $this->i18n);
        $count = 0;

        foreach ($users as $email) {
            if ($command->process($email)) {
                ++$count;
            }
        }
        $result = $command->result($count);
        $this->logEvent(sprintf('%s - %s', self::IDENTIFIER, $result));

        return $result;
    }

    /**
     * Extracts email addresses from an array of lines.
     * Lines without @ are ignored.
     *
     * @return array email addresses
     */
    private function extractEmailAddresses(array $emails)
    {
        $emails = array_map('trim', $emails);

        return array_filter(
            $emails,
            function ($item) {
                return strpos($item, '@') !== false;
            }
        );
    }

    /**
     * Loads user email addresses from the uploaded file.
     *
     * @return array email addresses
     */
    private function loadUsersFromFile()
    {
        $emails = file($this->model->file['tmp_name'], FILE_SKIP_EMPTY_LINES);

        return $this->extractEmailAddresses($emails);
    }

    /**
     * Generates the html for a dropdown list of lists owned by the current admin.
     *
     * @param bool $disabled Whether the list should be disabled
     *
     * @return string the html
     */
    private function ownedListsDropDown($disabled)
    {
        $lists = iterator_to_array($this->dao->listsForOwner(null));

        return CHtml::dropDownList(
            'listId',
            $this->model->listId,
            array_column($lists, 'name', 'id'),
            array('disabled' => $disabled)
        );
    }

    /**
     * Generates the html for a group of radio buttons.
     *
     * @param bool $disabled Whether the buttons should be disabled
     *
     * @return string the html
     */
    private function commandRadioButtons($disabled)
    {
        $commandList = Factory::commandList($this->i18n, $this->ownedListsDropDown($disabled));

        return CHtml::radioButtonList(
            'command',
            $this->model->command,
            $commandList,
            array('separator' => '<br />', 'disabled' => $disabled)
        );
    }

    /**
     * Displays the second page.
     * For a POST processes the subscribers.
     */
    protected function actionDisplayUsers()
    {
        $this->model->setProperties($_SESSION[self::PLUGIN]);

        if (isset($_POST['submit'])) {
            $result = $this->processUsers($this->model->users, $this->model->command, $this->model->listId);
            $this->redirectExit(new PageURL(), array('result' => $result));
        }

        $cancel = new PageLink(new PageURL(null), $this->i18n->get('Cancel'), array('class' => 'button'));
        $params = array(
            'toolbar' => $this->toolbar->display(),
            'commandList' => $this->commandRadioButtons(self::HTML_DISABLED),
            'userArea' => CHtml::textArea('users', implode("\n", $this->model->users),
                array('rows' => '10', 'cols' => '30', 'disabled' => self::HTML_DISABLED)
            ),
            'formURL' => new PageURL(null, array('action' => 'displayUsers')),
            'cancel' => $cancel,
        );
        echo $this->render(dirname(__FILE__) . self::TEMPLATE_2, $params);
    }

    /**
     * Displays the first page including any error or result message.
     * For a POST validates the submission. On success redirects to the second page.
     */
    protected function actionDefault()
    {
        $params = array();

        if (isset($_POST['submit'])) {
            $error = '';

            switch ($_POST['submit']) {
                case 'Upload':
                    $error = $this->validateFile();

                    if ($error == '') {
                        $users = $this->loadUsersFromFile();
                    }
                    break;
                case 'Process':
                    if ($this->model->emails == '') {
                        $error = $this->i18n->get('emails not entered');
                        break;
                    }
                    $users = $this->extractEmailAddresses(explode("\n", $this->model->emails));

                    if (count($users) === 0) {
                        $error = $this->i18n->get('no valid email addresses entered');
                    }
                    break;
                case 'Match':
                    if ($this->model->pattern == '') {
                        $error = $this->i18n->get('error_match_not_entered');
                        break;
                    }
                    $users = $this->dao->matchUsers(
                        $this->model->pattern,
                        $this->model->command == Factory::COMMAND_REMOVE
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

            if ($error === '') {
                $this->redirectExit(
                    new PageURL(null, array('action' => 'displayUsers')),
                    array(
                        'users' => $users,
                        'command' => $this->model->command,
                        'listId' => $this->model->listId,
                    )
                );
            }
            $params['error'] = $error;
        }

        if (isset($_SESSION[self::PLUGIN]['result'])) {
            $params['result'] = $_SESSION[self::PLUGIN]['result'];
        }
        unset($_SESSION[self::PLUGIN]);

        $params['toolbar'] = $this->toolbar->display();
        $params['formURL'] = new PageURL();
        $params['commandList'] = $this->commandRadioButtons(self::HTML_ENABLED);
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    public function __construct()
    {
        parent::__construct();
        $this->dao = new DAO(new DB());
        $this->model = new Model(Factory::COMMAND_UNCONFIRM);
        $this->model->setProperties($_REQUEST);
        $this->toolbar = new Toolbar($this);
        $this->toolbar->addExternalHelpButton(self::HELP);
    }
}
