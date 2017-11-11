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
 *  This class implements the command to confirm a subscriber.
 */
class Confirm extends Base
{
    public function accept(array $user)
    {
        return $user['confirmed'] == 0;
    }

    public function process(array $user)
    {
        $email = $user['email'];

        if (!$this->dao->confirmUser($email)) {
            return false;
        }
        addUserHistory(
            $email,
            Controller::IDENTIFIER,
            $this->i18n->get('history_confirmed')
        );

        return true;
    }

    public function result($count)
    {
        return $this->i18n->get('result_confirmed', $count);
    }
}
