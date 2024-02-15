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

use phpList\plugin\SubscribersPlugin\Controller\Command as Controller;

/**
 *  This class implements the command to remove a subscriber from a list.
 */
class Remove extends Base
{
    private $listId;
    private $listName;

    public function initialise()
    {
        if (isset($this->additionalFields['command'][$this->commandId]['listId'])) {
            $this->listId = $this->additionalFields['command'][$this->commandId]['listId'];
            $this->listName = $this->dao->listName($this->listId);
        } else {
            $this->listId = 0;
        }
    }

    public function accept(array $user)
    {
        return (bool) $this->dao->isUserOnList($user['id'], $this->listId);
    }

    public function process(array $user)
    {
        $this->dao->removeSubscriberFromList($user['id'], $this->listId);
        addUserHistory(
            $user['email'],
            Controller::IDENTIFIER,
            $this->i18n->get('history_removed', $this->listName)
        );

        return true;
    }

    public function result($count)
    {
        return $this->i18n->get('result_removed', $this->listName, $count);
    }

    public function additionalCommandHtml($disabled)
    {
        return $this->listsDropDown($this->listId, 'listId', $disabled);
    }
}
