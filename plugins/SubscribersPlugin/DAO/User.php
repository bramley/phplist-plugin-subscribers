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
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * DAO class that provides access to the user, user_attribute and related tables.
 */
class User extends DAO
{
    /**
     * The search term may have multiple values separated by , or +.
     * This method builds an expression containing a sub-expression for each value in the search term.
     *
     * @param string $searchTerm   single or multiple value search target
     * @param string $exprTemplate expression template for use by sprintf
     *
     * @return string
     */
    private function searchExpression($searchTerm, $exprTemplate)
    {
        if (substr($searchTerm, 0, 1) == '!') {
            $not = 'NOT ';
            $searchTerm = substr($searchTerm, 1);
        } else {
            $not = '';
        }

        if (strpos($searchTerm, '+') === false) {
            $separator = '|';
            $combineOp = "\nOR ";
        } else {
            $separator = '+';
            $combineOp = "\nAND ";
        }
        $terms = explode($separator, $searchTerm);
        $expressions = [];

        foreach ($terms as $term) {
            $expressions[] = sprintf($exprTemplate, $term);
        }
        $combined = implode($combineOp, $expressions);

        return "$not($combined)";
    }

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
     * Generates a list of join expressions for the FROM table references, a list of attribute fields for the SELECT
     * expression, and conditions for the WHERE clause.
     *
     * The user_attribute table is left joined for each attribute to allow for subscribers not having an attribute value.
     *
     * @param array  $attributes
     * @param string $searchTerm optional attribute value to be matched
     * @param int    $searchAttr optional attribute id to be matched
     *
     * @return array [0] joins for the FROM table references
     *               [1] attribute fields for the SELECT expression
     *               [2] conditions for the WHERE clause
     */
    private function userAttributeJoin($attributes, $searchTerm, $searchAttr, $searchIsRegex, $orderAttr)
    {
        $attr_fields = '';
        $attr_join = '';
        $attr_where = [];
        $orderBy = '';
        $doSearch = $searchTerm !== '';

        foreach ($attributes as $attr) {
            $id = $attr['id'];
            $tableName = $this->table_prefix . 'listattr_' . $attr['tablename'];
            $attr_join .= "\n";

            switch ($attr['type']) {
                case 'radio':
                case 'select':
                    if ($doSearch && $searchAttr == $id) {
                        $template = "COALESCE(la{$id}.name, '') LIKE '%%%s%%'";
                        $expr = $this->searchExpression($searchTerm, $template);
                        $attr_where[] = $expr;
                    }
                    $attr_join .= <<<END
        LEFT JOIN ({$this->tables['user_attribute']} ua{$id} JOIN {$tableName} la{$id} ON la{$id}.id = ua{$id}.value)
            ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}
END;
                    $attr_fields .= ", la{$id}.name as attr{$id}";

                    if ($orderAttr == $id) {
                        $orderBy = "la{$id}.name";
                    }
                    break;
                case 'checkboxgroup':
                    if ($doSearch && $searchAttr == $id) {
                        /*
                         * search term can have multiple values
                         * want to select subscribers whose attribute value matches any/all of the terms
                         */
                        $template = <<<END
        FIND_IN_SET(
            IFNULL(
                (SELECT id
                FROM $tableName
                WHERE name = '%s'),
                0
            ),
            IFNULL(ua{$id}.value, '')
        ) > 0
END;
                        $expr = $this->searchExpression($searchTerm, $template);
                        $attr_where[] = $expr;
                    }
                    $attr_join .= <<<END
        LEFT JOIN {$this->tables['user_attribute']} ua{$id} ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}
END;
                    $attr_fields .= ", ua{$id}.value as attr{$id}";

                    if ($orderAttr == $id) {
                        $orderBy = "ua{$id}.value";
                    }
                    break;
                case 'checkbox':
                    if ($doSearch && $searchAttr == $id) {
                        $op = $searchTerm == 'on' ? '=' : '!=';
                        $attr_where[] = "COALESCE(ua{$id}.value, '') $op 'on'";
                    }
                    $attr_join .= <<<END
        LEFT JOIN {$this->tables['user_attribute']} ua{$id} ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}
END;
                    $attr_fields .= ", ua{$id}.value as attr{$id}";

                    if ($orderAttr == $id) {
                        $orderBy = "ua{$id}.value";
                    }
                    break;
                default:
                    if ($doSearch && $searchAttr == $id) {
                        if ($searchIsRegex) {
                            $attr_where[] = sprintf("COALESCE(ua{$id}.value, '') REGEXP '%s'", $searchTerm);
                        } else {
                            $template = "COALESCE(ua{$id}.value, '') LIKE '%%%s%%'";
                            $expr = $this->searchExpression($searchTerm, $template);
                            $attr_where[] = $expr;
                        }
                    }
                    $attr_join .= <<<END
        LEFT JOIN {$this->tables['user_attribute']} ua{$id} ON ua{$id}.userid = u.id AND ua{$id}.attributeid = {$id}
END;
                    $attr_fields .= ", REPLACE(ua{$id}.value, '\\\\', '') AS attr{$id}";

                    if ($orderAttr == $id) {
                        $orderBy = "ua{$id}.value";
                    }
                    break;
            }
        }

        return array($attr_join, $attr_fields, $attr_where, $orderBy);
    }

    public function users($listID, $owner, $attributes, $searchTerm, $searchAttr, $searchIsRegex, $orderAttr,
        $confirmed = 0, $blacklisted = 0, $start = null, $limit = null)
    {
        $doSearch = $searchTerm !== '';
        $searchTerm = sql_escape($searchTerm);
        list($attr_join, $attr_fields, $attr_where, $orderBy) = $this->userAttributeJoin($attributes, $searchTerm, $searchAttr, $searchIsRegex, $orderAttr);
        $limitClause = is_null($start) ? '' : "LIMIT $start, $limit";

        if (ctype_digit($orderAttr)) {
            // already set
        } else {
            switch ($orderAttr) {
                case 'id':
                    $orderBy = 'u.id';
                    break;
                case 'uniqid':
                    $orderBy = 'u.uniqid';
                    break;
                case 'subspage':
                    $orderBy = 'u.subscribepage';
                    break;
                case 'email':
                default:
                    $orderBy = 'u.email';
            }
        }
        $w = $attr_where;

        if ($doSearch) {
            if ($searchAttr == 'email') {
                if ($searchIsRegex) {
                    $w[] = sprintf("u.email REGEXP '%s'", $searchTerm);
                } else {
                    $template = "u.email LIKE '%%%s%%'";
                    $expr = $this->searchExpression($searchTerm, $template);
                    $w[] = $expr;
                }
            } elseif ($searchAttr == 'id') {
                $w[] = "u.id = '$searchTerm'";
            } elseif ($searchAttr == 'uniqid') {
                $w[] = "u.uniqid = '$searchTerm'";
            } elseif ($searchAttr == 'subspage') {
                $w[] = "u.subscribepage = '$searchTerm'";
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

        $sql = <<<END
            SELECT u.id, u.email, u.confirmed, u.blacklisted, u.htmlemail, u.uniqid, u.subscribepage, sp.title $attr_fields,
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
            LEFT JOIN {$this->tables['subscribepage']} sp ON sp.id = u.subscribepage
            $attr_join
            WHERE u.id IN (
                SELECT * FROM (
                    SELECT u.id
                    FROM {$this->tables['user']} u
                    $attr_join
                    $where
                    ORDER BY $orderBy
                    $limitClause
                ) AS t
            )
            ORDER BY $orderBy
END;

        return $this->dbCommand->queryAll($sql);
    }

    public function totalUsers($listID, $owner, $attributes, $searchTerm, $searchAttr, $searchIsRegex, $confirmed = 0, $blacklisted = 0)
    {
        $doSearch = $searchTerm !== '';
        $searchTerm = sql_escape($searchTerm);

        if ($doSearch) {
            list($attr_join, $attr_fields, $attr_where, $orderBy) = $this->userAttributeJoin($attributes, $searchTerm, $searchAttr, $searchIsRegex, null);
        } else {
            $attr_join = '';
            $attr_where = [];
        }
        $w = $attr_where;

        if ($doSearch) {
            if ($searchAttr == 'email') {
                if ($searchIsRegex) {
                    $w[] = sprintf("u.email REGEXP '%s'", $searchTerm);
                } else {
                    $template = "u.email LIKE '%%%s%%'";
                    $expr = $this->searchExpression($searchTerm, $template);
                    $w[] = $expr;
                }
            } elseif ($searchAttr == 'id') {
                $w[] = "u.id = '$searchTerm'";
            } elseif ($searchAttr == 'uniqid') {
                $w[] = "u.uniqid = '$searchTerm'";
            } elseif ($searchAttr == 'subspage') {
                $w[] = "u.subscribepage = '$searchTerm'";
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

        return $this->dbCommand->queryOne($sql);
    }

    /**
     * Look-up the names for a set of checkbox group attribute value ids
     * e.g. 1,3,4 => ['red', 'blue', 'yellow'].
     *
     * @param array  $attr   attribute
     * @param string $cbgIds comma-separated list of value ids
     *
     * @return array
     */
    public function cbgNames(array $attr, $cbgIds)
    {
        $tableName = $this->table_prefix . 'listattr_' . $attr['tablename'];
        $sql = <<<END
SELECT name
FROM $tableName
WHERE id IN ($cbgIds)
END;

        return $this->dbCommand->queryColumn($sql);
    }

    /**
     * Validate the syntax of a regular expression by trying to use it in a query.
     *
     * @return bool
     */
    public function isRegexValid($regex)
    {
        $regex = sql_escape($regex);
        $sql = "SELECT '' REGEXP '$regex'";

        try {
            $this->dbCommand->queryOne($sql);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
