<?php

namespace phpList\plugin\SubscribersPlugin\DAO;

use phpList\plugin\Common\DAO;

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
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * DAO class that provides access to the user, user_attribute and related tables.
 * 
 * @category  phplist
 */
class User extends DAO
{
    /**
     * Generates a WHERE expression for the user belonging to the specified list and 
     * optionally the list owned by the specified owner.
     *
     * @param int    $listID
     * @param string $loginid
     *
     * @return string WHERE expression
     */
    private function list_exists($listID, $loginid)
    {
        if ($listID || $loginid) {
            $owner = $loginid ? "AND l.owner = $loginid" : '';
            $list = $listID ? "AND l.id = $listID" : '';
            $where =
                "EXISTS (
                    SELECT 1 from {$this->tables['listuser']} lu, {$this->tables['list']} l
                    WHERE u.id = lu.userid AND lu.listid = l.id $list $owner
                )";
        } else {
            $where = '';
        }

        return $where;
    }

    /**
     * Generates a list of join expressions for the FROM table references and a list of attribute fields for the SELECT expression.
     *
     * @param array  $attributes
     * @param string $searchTerm optional attribute value to be matched
     * @param int    $searchAttr optional attribute id to be matched
     *
     * @return string WHERE expression
     */
    private function userAttributeJoin($attributes, $searchTerm, $searchAttr)
    {
        $attr_fields = '';
        $attr_join = '';
        $doSearch = $searchTerm !== '';

        foreach ($attributes as $attr) {
            $id = $attr['id'];
            $tableName = $this->table_prefix . 'listattr_' . $attr['tablename'];

            $thisJoin = "\n" . (($doSearch && $searchAttr == $id) ? '' : 'LEFT ');

            switch ($attr['type']) {
            case 'radio':
            case 'select':
                $thisJoin .= "JOIN ({$this->tables['user_attribute']} ua{$id} JOIN {$tableName} la{$id} ON la{$id}.id = ua{$id}.value)
                    ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}";

                if ($doSearch && $searchAttr == $id) {
                    $thisJoin .= " AND la{$id}.name LIKE '%$searchTerm%'";
                }
                $attr_fields .= ", la{$id}.name as attr{$id}";
                break;
            case 'checkboxgroup':
                $thisJoin .= "JOIN {$this->tables['user_attribute']} ua{$id} 
                    ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}";

                if ($doSearch && $searchAttr == $id) {
                    $thisJoin .= " AND FIND_IN_SET('$searchTerm', COALESCE(ua{$id}.value, '')) > 0";
                }
                $attr_fields .= ", ua{$id}.value as attr{$id}";
                break;
            default:
                $thisJoin .= "JOIN {$this->tables['user_attribute']} ua{$id} 
                    ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}";

                if ($doSearch && $searchAttr == $id) {
                    $thisJoin .= " AND ua{$id}.value LIKE '%$searchTerm%' ";
                }
                $attr_fields .= ", ua{$id}.value as attr{$id}";
                break;
            }
            $attr_join .= $thisJoin;
        }

        return array($attr_join, $attr_fields);
    }

    public function users($listID, $owner, $attributes, $searchTerm, $searchAttr,
        $confirmed = 0, $blacklisted = 0, $start = null, $limit = null)
    {
        /*
         * 
         */
        $doSearch = $searchTerm !== '';
        $searchTerm = sql_escape($searchTerm);
        list($attr_join, $attr_fields) = $this->userAttributeJoin($attributes, $searchTerm, $searchAttr);
        $limitClause = is_null($start) ? '' : "LIMIT $start, $limit";
        $w = array();

        if ($doSearch) {
            if ($searchAttr == 'email') {
                $w[] = "u.email LIKE '%$searchTerm%'";
            } elseif ($searchAttr == 'id') {
                $w[] = "u.id = '$searchTerm'";
            } elseif ($searchAttr == 'uniqid') {
                $w[] = "u.uniqid = '$searchTerm'";
            }
        }

        if ($le = $this->list_exists($listID, $owner)) {
            $w[] = $le;
        }

        if ($confirmed == 2) {
            $w[] = 'u.confirmed = 1';
        } elseif ($confirmed == 3) {
            $w[] = 'u.confirmed = 0';
        }

        if ($blacklisted == 2) {
            $w[] = 'u.blacklisted = 1';
        } elseif ($blacklisted == 3) {
            $w[] = 'u.blacklisted = 0';
        }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';

        $sql =
            "SELECT u.id, u.email, u.confirmed, u.blacklisted, u.htmlemail, u.uniqid $attr_fields,
                (SELECT count(lu.listid)
                FROM {$this->tables['listuser']} lu
                WHERE lu.userid = u.id
                ) AS lists,
                (SELECT COUNT(*)
                FROM {$this->tables['usermessage']}
                WHERE userid = u.id AND status = 'sent'
                ) AS sent,
                (SELECT COUNT(viewed)
                FROM {$this->tables['usermessage']}
                WHERE userid = u.id
                ) AS opens,
                (SELECT COUNT(DISTINCT messageid)
                FROM {$this->tables['linktrack_uml_click']}
                WHERE userid = u.id
                ) AS clicks
            FROM {$this->tables['user']} u
            $attr_join
            $where
            ORDER by u.id
            $limitClause";

        return $this->dbCommand->queryAll($sql);
    }

    public function totalUsers($listID, $owner, $attributes, $searchTerm, $searchAttr, $confirmed = 0, $blacklisted = 0)
    {
        $doSearch = $searchTerm !== '';
        $searchTerm = sql_escape($searchTerm);

        if ($doSearch) {
            list($attr_join) = $this->userAttributeJoin($attributes, $searchTerm, $searchAttr);
        } else {
            $attr_join = '';
        }
        $w = array();

        if ($doSearch) {
            if ($searchAttr == 'email') {
                $w[] = "u.email LIKE '%$searchTerm%'";
            } elseif ($searchAttr == 'id') {
                $w[] = "u.id = '$searchTerm'";
            } elseif ($searchAttr == 'uniqid') {
                $w[] = "u.uniqid = '$searchTerm'";
            }
        }

        if ($le = $this->list_exists($listID, $owner)) {
            $w[] = $le;
        }

        if ($confirmed == 2) {
            $w[] = 'u.confirmed = 1';
        } elseif ($confirmed == 3) {
            $w[] = 'u.confirmed = 0';
        }

        if ($blacklisted == 2) {
            $w[] = 'u.blacklisted = 1';
        } elseif ($blacklisted == 3) {
            $w[] = 'u.blacklisted = 0';
        }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';

        $sql = "SELECT count(*) as t 
            FROM {$this->tables['user']} u
            $attr_join
            $where";

        return $this->dbCommand->queryOne($sql, 't');
    }
}
