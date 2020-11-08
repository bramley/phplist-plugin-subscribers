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

namespace phpList\plugin\SubscribersPlugin\Command;

/**
 *  This class creates the command classes.
 */
class Factory
{
    const COMMAND_UNCONFIRM = 1;
    const COMMAND_BLACKLIST = 2;
    const COMMAND_DELETE = 3;
    const COMMAND_REMOVE = 4;
    const COMMAND_UNBLACKLIST = 5;
    const COMMAND_RESEND_CONFIRMATION_REQUEST = 6;
    const COMMAND_CHANGE_SUBSCRIBE_PAGE = 7;
    const COMMAND_RESET_BOUNCE_COUNT = 8;
    const COMMAND_CONFIRM = 9;
    const COMMAND_REMOVE_All = 10;
    const COMMAND_MOVE = 11;

    /**
     * Constructor.
     *
     * @param \phpList\plugin\SubscribersPlugin\DAO\Command $dao
     * @param \phpList\plugin\Common\I18N                   $i18n
     */
    public function __construct($dao = null, $i18n = null)
    {
        $this->dao = $dao;
        $this->i18n = $i18n;
    }

    /**
     * Creates a command.
     *
     * @param int   $commandId
     * @param array $additionalFields
     *
     * @return Base instance of command
     */
    public function createCommand($commandId, $additionalFields = [])
    {
        switch ($commandId) {
            case self::COMMAND_UNCONFIRM:
                $command = new Unconfirm($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_BLACKLIST:
                $command = new Blacklist($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_DELETE:
                $command = new Delete($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_REMOVE:
                $command = new Remove($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_REMOVE_All:
                $command = new RemoveAll($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_UNBLACKLIST:
                $command = new Unblacklist($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_RESEND_CONFIRMATION_REQUEST:
                $command = new Resend($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_CHANGE_SUBSCRIBE_PAGE:
                $command = new SubscribePage($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_RESET_BOUNCE_COUNT:
                $command = new ResetBounceCount($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_CONFIRM:
                $command = new Confirm($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            case self::COMMAND_MOVE:
                $command = new Move($commandId, $additionalFields, $this->dao, $this->i18n);
                break;
            default:
                throw new \Exception("Unrecognised command id - $commandId");
        }

        return $command;
    }

    /**
     * Returns a list of available commands.
     *
     * @param array $additionalFields
     * @param bool  $disabled
     *
     * @return array command id => command caption
     */
    public function availableCommands($additionalFields, $disabled)
    {
        $commandList = [
            self::COMMAND_CONFIRM => $this->i18n->get('Confirm'),
            self::COMMAND_UNCONFIRM => $this->i18n->get('Unconfirm'),
            self::COMMAND_BLACKLIST => $this->i18n->get('Blacklist'),
            self::COMMAND_UNBLACKLIST => $this->i18n->get('Unblacklist'),
            self::COMMAND_DELETE => $this->i18n->get('Delete'),
            self::COMMAND_MOVE => $this->i18n->get('Move between lists'),
            self::COMMAND_REMOVE => $this->i18n->get('Remove from list'),
            self::COMMAND_REMOVE_All => $this->i18n->get('Remove from all subscribed lists'),
            self::COMMAND_RESEND_CONFIRMATION_REQUEST => $this->i18n->get('Resend confirmation request'),
            self::COMMAND_CHANGE_SUBSCRIBE_PAGE => $this->i18n->get('Change subscribe page'),
            self::COMMAND_RESET_BOUNCE_COUNT => $this->i18n->get('Reset bounce count'),
        ];

        foreach ($commandList as $commandId => &$caption) {
            $command = $this->createCommand($commandId, $additionalFields);

            if ($additionalHtml = $command->additionalCommandHtml($disabled)) {
                $caption .= ' ' . $additionalHtml;
            }
        }

        return $commandList;
    }
}
