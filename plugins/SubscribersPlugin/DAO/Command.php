<?php

namespace phpList\plugin\SubscribersPlugin\DAO;

use phpList\plugin\Common\DAO\Lists as DAOList;
use phpList\plugin\Common\DAO\User;

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
class Command extends User
{
    private $listDAO;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->listDAO = new DAOList($db);
    }

    public function listsForOwner($loginid)
    {
        return $this->listDAO->listsForOwner($loginid);
    }

    public function listName($listId)
    {
        $list = $this->listDAO->listById($listId);

        return $list['name'];
    }

    public function matchUsers($pattern, $listId = null)
    {
        $luJoin = is_null($listId) ? '' : "JOIN {$this->tables['listuser']} lu ON u.id = lu.userid AND lu.listid = $listId";

        $pattern = sql_escape($pattern);
        $sql =
            "SELECT u.email as email, u.id
            FROM {$this->tables['user']} u
            $luJoin
            WHERE u.email LIKE '%$pattern%'
            ";

        return $this->dbCommand->queryColumn($sql, 'email');
    }

    public function allUsers()
    {
        $sql =
            "SELECT email, id
            FROM {$this->tables['user']} u
            ";

        return $this->dbCommand->queryAll($sql);
    }

    public function countInactiveSubscribersByInterval($interval)
    {
        $sql =
            "SELECT COUNT(*)
            FROM (
                SELECT 1
                FROM {$this->tables['user']} u
                JOIN {$this->tables['usermessage']} um ON um.userid = u.id
                WHERE um.status = 'sent' AND um.entered > DATE_SUB(CURDATE(), INTERVAL $interval)
                GROUP BY u.id
                HAVING MAX(um.viewed) IS NULL
            ) AS t1";

        return $this->dbCommand->queryOne($sql);
    }

    public function inactiveSubscribersByInterval($interval, $start = null, $limit = null)
    {
        $limit = is_null($start) ? '' : "LIMIT $start, $limit";
        $sql =
            "SELECT u.id, email, COUNT(u.id) AS recent_campaigns,
                (SELECT COUNT(*)
                FROM {$this->tables['usermessage']} um
                WHERE um.userid = u.id and status = 'sent'
                ) AS total_campaigns,
                (SELECT MAX(viewed)
                FROM {$this->tables['usermessage']} um
                WHERE um.userid = u.id
                ) AS lastview,
                (SELECT GROUP_CONCAT(name)
                FROM {$this->tables['listuser']} lu 
                JOIN {$this->tables['list']} l ON lu.listid = l.id
                WHERE lu.userid = u.id
                ) AS listname
            FROM {$this->tables['user']} u
            JOIN {$this->tables['usermessage']} um ON um.userid = u.id
            WHERE um.status = 'sent' AND um.entered > DATE_SUB(CURDATE(), INTERVAL $interval)
            GROUP BY u.id
            HAVING MAX(um.viewed) IS NULL
            ORDER BY lastview, email
            $limit
            ";

        return $this->dbCommand->queryAll($sql);
    }

    public function inactiveSubscribersByCampaigns($threshold)
    {
        $sql = <<<END
            select u.id, u.email,
            (
                select count(um.userid)
                from {$this->tables['usermessage']} um 
                where u.id = um.userid
                and um.status = 'sent'
                -- handle no rows being viewed, max then returns null
                and ifnull(
                    um.entered > 
                        (select max(entered) from {$this->tables['usermessage']}
                        where userid = u.id and status = 'sent' and viewed is not null
                        ),
                    true
                )
            ) AS total
            from {$this->tables['user']} u
            WHERE total >= $threshold
            ORDER BY email
END;

        return $this->dbCommand->queryAll($sql);
    }

    public function removeFromList($email, $listId)
    {
        $email = sql_escape($email);
        $sql =
            "DELETE lu
            FROM {$this->tables['listuser']} lu
            JOIN {$this->tables['user']} u ON u.id = lu.userid
            WHERE u.email = '$email'
            AND lu.listid = $listId
            ";

        return $this->dbCommand->queryAffectedRows($sql);
    }
}
