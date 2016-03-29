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
 * @copyright 2011-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */
class SubscribersPlugin_DAO_Command extends CommonPlugin_DAO_User
{
    private $listDAO;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->listDAO = new CommonPlugin_DAO_List($db);
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
