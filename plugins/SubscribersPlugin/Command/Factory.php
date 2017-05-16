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

    /**
     * Applies the command to the set of subscribers.
     *
     * @param int                                           $commandId
     * @param int                                           $listId
     * @param \phpList\plugin\SubscribersPlugin\DAO\Command $dao
     * @param \phpList\plugin\Common\I18N                   $i18n
     *
     * @return Base instance of command
     */
    public static function createCommand($commandId, $listId, $dao, $i18n)
    {
        switch ($commandId) {
            case self::COMMAND_UNCONFIRM:
                $command = new Unconfirm($dao, $i18n);
                break;
            case self::COMMAND_BLACKLIST:
                $command = new Blacklist($dao, $i18n);
                break;
            case self::COMMAND_DELETE:
                $command = new Delete($dao, $i18n);
                break;
            case self::COMMAND_REMOVE:
                $command = new Remove($dao, $i18n, $listId);
                break;
            case self::COMMAND_UNBLACKLIST:
                $command = new Unblacklist($dao, $i18n);
                break;
            case self::COMMAND_RESEND_CONFIRMATION_REQUEST:
                $command = new Resend($dao, $i18n);
                break;
            default:
                throw new Exception("Unrecognised command id - $commandId");
        }

        return $command;
    }

    /**
     * Returns a list of available commands..
     *
     * @param \phpList\plugin\Common\I18N $i18n
     * @param string                      $dropDownList
     *
     * @return array command id => command caption
     */
    public static function commandList($i18n, $dropDownList)
    {
        return [
            self::COMMAND_UNCONFIRM => $i18n->get('Unconfirm'),
            self::COMMAND_BLACKLIST => $i18n->get('Blacklist'),
            self::COMMAND_UNBLACKLIST => $i18n->get('Unblacklist'),
            self::COMMAND_DELETE => $i18n->get('Delete'),
            self::COMMAND_RESEND_CONFIRMATION_REQUEST => $i18n->get('Resend confirmation request'),
            self::COMMAND_REMOVE => $i18n->get('Remove from list') . '&nbsp;' . $dropDownList,
        ];
    }
}
