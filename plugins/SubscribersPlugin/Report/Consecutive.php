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

namespace phpList\plugin\SubscribersPlugin\Report;

use ArrayIterator;

/**
 * {@inheritdoc}
 */
class Consecutive extends AbstractReport
{
    public $showRefresh = true;

    public function getIterator($dao)
    {
        if (!isset($_SESSION['consecutive_bounces'])) {
            $_SESSION['consecutive_bounces'] = iterator_to_array(consecutiveBouncesGenerator());
        }

        return new ArrayIterator($_SESSION['consecutive_bounces']);
    }

    public function refresh()
    {
        unset($_SESSION['consecutive_bounces']);
    }

    public function title()
    {
        return $this->i18n->get('Consecutive bounces');
    }

    public function noSubscribersWarning()
    {
        return $this->i18n->get('No subscribers have consecutive bounces');
    }

    public function columnCallback()
    {
        return function () {
            return [
                $this->i18n->get('Consecutive bounces'),
            ];
        };
    }

    public function valuesCallback()
    {
        return function ($row) {
            return [
                $row['consecutive'],
            ];
        };
    }
}

function consecutiveBouncesGenerator($consecutiveLimit = 0)
{
    global $tables;

    $userid_req = Sql_query(sprintf(
        'select u.id, u.email
        from %s umb
        join %s u on umb.user = u.id
        where u.confirmed and !u.blacklisted
        group by u.id',
        $tables['user_message_bounce'],
        $tables['user']
    ));

    while ($user = Sql_Fetch_Array($userid_req)) {
        $msg_req = Sql_Query(sprintf(
            'select umb.bounce, b.id, b.status, b.comment
            from %s um
            left join %s umb on (um.messageid = umb.message and um.userid = umb.user)
            left join %s b on umb.bounce = b.id
            where userid = %d and um.status = "sent"
            order by um.entered desc',
            $tables['usermessage'],
            $tables['user_message_bounce'],
            $tables['bounce'],
            $user['id']
        ));
        $consecutive = 0;

        while ($bounce = Sql_Fetch_Array($msg_req)) {
            if ($bounce['bounce'] === null) {
                // message did not bounce, finish consecutive counting
                break;
            }

            if ($bounce['id'] === null
                || (stripos($bounce['status'], 'duplicate') === false
                    && stripos($bounce['comment'], 'duplicate') === false)) {
                // count when bounce does not now exist or exists and is not a duplicate
                ++$consecutive;

                if ($consecutiveLimit > 0 && $consecutive == $consecutiveLimit) {
                    break;
                }
            }
        }

        if ($consecutive > 0) {
            yield ['email' => $user['email'], 'id' => $user['id'], 'consecutive' => $consecutive];
        }
    }
}
