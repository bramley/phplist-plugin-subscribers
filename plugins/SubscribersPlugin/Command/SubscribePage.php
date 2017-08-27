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
 *  This class implements the command to change the subscribe page of a user.
 */
class SubscribePage extends Base
{
    public function initialise()
    {
        if (isset($this->additionalFields['command'][$this->commandId]['pageId'])) {
            $this->pageId = $this->additionalFields['command'][$this->commandId]['pageId'];
        } else {
            $this->pageId = 0;
        }
    }

    public function accept(array $user)
    {
        return $user['subscribepage'] !== $this->pageId;
    }

    public function process(array $user)
    {
        $this->dao->updateSubscribePage($user['id'], $this->pageId);
        addUserHistory(
            $user['email'],
            Controller::IDENTIFIER,
            $this->i18n->get('history_subscribe_page', $user['subscribepage'], $this->pageId)
        );

        return true;
    }

    public function result($count)
    {
        return $this->i18n->get('result_subscribe_page_changed', $count);
    }

    public function additionalCommandHtml($disabled)
    {
        $pages = [];

        foreach ($this->dao->subscribePages() as $row) {
            $pages[$row['id']] = $row['id'] . ' ' . $row['title'];
        }

        return CHtml::dropDownList(
            sprintf('additional[command][%d][pageId]', $this->commandId),
            $this->pageId,
            $pages,
            array('disabled' => $disabled)
        );
    }
}
