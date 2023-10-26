<?php

namespace phpList\plugin\SubscribersPlugin\DAO;

use phpList\plugin\Common\DAO;

/**
 * SubscribersPlugin for phplist.
 *
 * This file is a part of SubscribersPlugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * DAO class that provides access to the user_history and related tables.
 *
 * @category  phplist
 */
class Event extends DAO
{
    public function listEvents($type, $param, $start, $limit)
    {
        $param = sql_escape($param);
        $limit = is_null($start) ? '' : "LIMIT $start, $limit";

        switch ($type) {
            case 'date':
                $order = 'ASC';
                $where = "WHERE date >= '$param'";
                break;
            case 'pattern':
                $order = 'ASC';
                $where = $param ? "WHERE uh.summary RLIKE '$param' OR uh.detail RLIKE '$param' OR INSTR(uh.ip, '$param') > 0" : '';
                break;
            default:
                $order = 'DESC';
                $where = '';
        }

        $sql =
            "SELECT u.email, uh.*
            FROM {$this->tables['user_history']} uh
            JOIN {$this->tables['user']} u ON uh.userid = u.id
            $where
            ORDER BY id $order
            $limit";

        return $this->dbCommand->queryAll($sql);
    }

    public function totalEvents($type, $param = null)
    {
        $param = sql_escape($param);
        if ($type == 'date') {
            $where = "WHERE date >= '$param'";
        } else {
            $where = $param ? "WHERE uh.summary RLIKE '$param' OR uh.detail RLIKE '$param' OR INSTR(uh.ip, '$param') > 0" : '';
        }

        $sql = "SELECT count(*) as t
            FROM {$this->tables['user_history']} uh
            JOIN {$this->tables['user']} u ON uh.userid = u.id
            $where";

        return $this->dbCommand->queryOne($sql);
    }
}
