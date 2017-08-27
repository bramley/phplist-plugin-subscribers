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
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\Controller;

use CHtml;
use phpList\plugin\Common\Controller;
use phpList\plugin\Common\PageLink;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\SubscribersPlugin\Command\Factory;
use phpList\plugin\SubscribersPlugin\DAO\Command as DAO;
use phpList\plugin\SubscribersPlugin\Model\Command as Model;

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
     * @param array  $session  variables to be added to, or replaced in, the session
     */
    private function redirectExit($redirect, array $session = [])
    {
        if (isset($_SESSION[self::PLUGIN])) {
            $_SESSION[self::PLUGIN] = array_merge($_SESSION[self::PLUGIN], $session);
        } else {
            $_SESSION[self::PLUGIN] = $session;
        }
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
            $errorText = [
                1 => $this->i18n->get('upload_error_1'),
                2 => $this->i18n->get('upload_error_2'),
                3 => $this->i18n->get('upload_error_3'),
                4 => $this->i18n->get('upload_error_4'),
                6 => $this->i18n->get('upload_error_6'),
            ];
            $error = $errorText[$f['error']];
        } elseif (!preg_match('/csv|text/', $f['type'])) {
            $error = $this->i18n->get('error_extension');
        } elseif ($f['size'] == 0) {
            $error = $this->i18n->get('error_empty', $f['name']);
        }

        return $error;
    }

    /**
     * Allows the command to decide whether to accept for processing each of the
     * entered subscriber email addresses.
     *
     * @param array $emails email addresses
     *
     * @return array the subscribers who have been accepted
     */
    private function acceptEmails(array $emails)
    {
        $command = $this->factory->createCommand($this->model->commandid, $this->model->additional);
        $accepted = array_filter(
            $emails,
            function ($email) use ($command) {
                $user = $this->dao->userByEmail($email);

                if (!$user) {
                    return false;
                }

                return $command->accept($user);
            }
        );

        return $accepted;
    }

    /**
     * Applies the command to the set of subscribers.
     *
     * @param phpList\plugin\SubscribersPlugin\Command\Base $command instance of command
     *
     * @return string a message summarising the command and number of affected subscribers
     */
    private function processAcceptedEmails($command)
    {
        $count = 0;

        foreach ($this->model->acceptedEmails as $email) {
            $user = $this->dao->userByEmail($email);

            if ($command->process($user)) {
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
     * Generates the html for a group of radio buttons.
     * For clarity, when the control is disabled then include only the selected command.
     *
     * @param bool $disabled Whether the buttons should be disabled
     *
     * @return string the html
     */
    private function commandRadioButtons($disabled)
    {
        $commandList = $this->factory->availableCommands($this->model->additional, $disabled);

        $commands = $disabled
            ? [$this->model->commandid => $commandList[$this->model->commandid]]
            : $commandList;

        return CHtml::radioButtonList(
            'commandid',
            $this->model->commandid,
            $commands,
            ['separator' => '<br />', 'disabled' => $disabled]
        );
    }

    /**
     * Validates the submission of the first page.
     * On success redirects to the second page. On error redirects to the same page.
     *
     * @return array [0] target for redirect
     *               [1] values to be stored in the session
     */
    private function handlePost()
    {
        $error = '';

        switch ($_POST['submit']) {
            case 'Upload':
                $error = $this->validateFile();

                if ($error == '') {
                    $emails = $this->loadUsersFromFile();
                }
                break;
            case 'Process':
                if ($this->model->emails == '') {
                    $error = $this->i18n->get('emails not entered');
                    break;
                }
                $emails = $this->extractEmailAddresses(explode("\n", $this->model->emails));

                if (count($emails) === 0) {
                    $error = $this->i18n->get('no valid email addresses entered');
                }
                break;
            case 'Match':
                if ($this->model->pattern == '') {
                    $error = $this->i18n->get('error_match_not_entered');
                    break;
                }
                $emails = $this->dao->matchUserPattern($this->model->pattern);

                if (count($emails) == 0) {
                    $error = $this->i18n->get('error_no_match', $this->model->pattern);
                    break;
                }
                break;
            default:
                $error = 'unrecognised submit ' . $_POST['submit'];
        }

        if ($error === '') {
            $acceptedEmails = $this->acceptEmails($emails);

            if (count($acceptedEmails) > 0) {
                return [
                    new PageURL(null, ['action' => 'displayUsers']),
                    [
                        'acceptedEmails' => $acceptedEmails,
                        'commandid' => $this->model->commandid,
                        'additional' => $this->model->additional,
                    ],
                ];
            }
            $error = $this->i18n->get('error_no_acceptable');
        }

        return [
            new PageURL(),
            [
                'error' => $error,
                'commandid' => $this->model->commandid,
                'additional' => $this->model->additional,
            ],
        ];
    }

    /**
     * Displays the second page.
     * For a POST processes the command and subscribers.
     */
    protected function actionDisplayUsers()
    {
        $this->model->setProperties($_SESSION[self::PLUGIN]);
        $command = $this->factory->createCommand($this->model->commandid, $this->model->additional);

        if (isset($_POST['submit'])) {
            $result = $this->processAcceptedEmails($command);
            $this->redirectExit(new PageURL(), ['result' => $result]);
        }
        $additionalHtml = $command->additionalHtml();
        $cancel = new PageLink(new PageURL(), $this->i18n->get('Cancel'), ['class' => 'button']);
        $params = [
            'toolbar' => $this->toolbar->display(),
            'commandList' => $this->commandRadioButtons(self::HTML_DISABLED),
            'userArea' => CHtml::textArea(
                'users',
                implode("\n", $this->model->acceptedEmails),
                ['rows' => '10', 'cols' => '30', 'disabled' => self::HTML_DISABLED]
            ),
            'additionalHtml' => $additionalHtml,
            'formURL' => new PageURL(null, ['action' => 'displayUsers']),
            'cancel' => $cancel,
            'subscriberCount' => count($this->model->acceptedEmails),
        ];
        echo $this->render(__DIR__ . self::TEMPLATE_2, $params);
    }

    /**
     * Displays the first page including any error or result message.
     * For a POST validates the entered command and subscribers.
     */
    protected function actionDefault()
    {
        if (isset($_POST['submit'])) {
            list($redirect, $session) = $this->handlePost();
            $this->redirectExit($redirect, $session);
        }
        $params = [];

        if (isset($_SESSION[self::PLUGIN])) {
            $this->model->setProperties($_SESSION[self::PLUGIN]);

            if (isset($_SESSION[self::PLUGIN]['result'])) {
                $params['result'] = $_SESSION[self::PLUGIN]['result'];
            }

            if (isset($_SESSION[self::PLUGIN]['error'])) {
                $params['error'] = $_SESSION[self::PLUGIN]['error'];
            }
            unset($_SESSION[self::PLUGIN]);
        }
        $params['toolbar'] = $this->toolbar->display();
        $params['formURL'] = new PageURL();
        $params['commandList'] = $this->commandRadioButtons(self::HTML_ENABLED);
        echo $this->render(__DIR__ . self::TEMPLATE, $params);
    }

    public function __construct(DAO $dao, Model $model)
    {
        parent::__construct();
        $this->dao = $dao;
        $this->model = $model;
        $this->model->setProperties($_POST);
        $this->toolbar = new Toolbar($this);
        $this->toolbar->addExternalHelpButton(self::HELP);
        $this->factory = new Factory($this->dao, $this->i18n);
    }
}
