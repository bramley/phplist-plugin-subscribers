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

use CHtml;
use phpList\plugin\SubscribersPlugin\Controller\Command as Controller;

/**
 *  This class implements the command to remove a subscriber from a list.
 */
class Move extends Base
{
    private $fromListId;
    private $toListId;
    private $removedCount;
    private $addedCount;

    public function initialise()
    {
        if (isset($this->additionalFields['command'][$this->commandId]['fromlistId'])) {
            $this->fromListId = $this->additionalFields['command'][$this->commandId]['fromlistId'];
            $this->fromListName = $this->dao->listName($this->fromListId);
        } else {
            $this->fromListId = 0;
        }

        if (isset($this->additionalFields['command'][$this->commandId]['tolistId'])) {
            $this->toListId = $this->additionalFields['command'][$this->commandId]['tolistId'];
            $this->toListName = $this->dao->listName($this->toListId);
        } else {
            $this->toListId = 0;
        }
    }

    public function validate()
    {
        if ($this->fromListId !== 0 && $this->fromListId == $this->toListId) {
            throw new \Exception('From list and To list cannot be the same');
        }
    }

    public function accept(array $user)
    {
        return (bool) $this->dao->isUserOnList($user['id'], $this->fromListId);
    }

    public function process(array $user)
    {
        list($removed, $added) = $this->dao->moveBetweenLists($user['id'], $this->fromListId, $this->toListId);
        $this->removedCount += $removed;
        $this->addedCount += $added;
        addUserHistory(
            $user['email'],
            Controller::IDENTIFIER,
            $this->i18n->get('history_moved', $this->fromListName, $this->toListName)
        );

        return true;
    }

    public function result($count)
    {
        return $this->i18n->get('result_moved', $this->fromListName, $this->removedCount, $this->toListName, $this->addedCount);
    }

    public function additionalCommandHtml($disabled)
    {
        $lists = iterator_to_array($this->dao->listsForOwner(null));

        $fromDropDown = CHtml::dropDownList(
            sprintf('additional[command][%d][fromlistId]', $this->commandId),
            $this->fromListId,
            array_column($lists, 'name', 'id'),
            array('disabled' => $disabled)
        );
        $toDropDown = CHtml::dropDownList(
            sprintf('additional[command][%d][tolistId]', $this->commandId),
            $this->toListId,
            array_column($lists, 'name', 'id'),
            array('disabled' => $disabled)
        );

        return $fromDropDown . $toDropDown;
    }
}
