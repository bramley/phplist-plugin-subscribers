<?php

namespace phpList\plugin\SubscribersPlugin\DAO;

use phpList\plugin\Common\DAO;
use phpList\plugin\Common\DAO\AttributeTrait;
use phpList\plugin\Common\DAO\ListsTrait;
use phpList\plugin\Common\DAO\ListUserTrait;
use phpList\plugin\Common\DAO\UserTrait;

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
class Command extends DAO
{
    use AttributeTrait;
    use ListsTrait;
    use ListUserTrait;
    use UserTrait;

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function listName($listId)
    {
        $list = $this->listById($listId);

        return $list['name'];
    }

    public function matchUserPattern($pattern)
    {
        if (substr($pattern, 0, 1) == '!') {
            $pattern = substr($pattern, 1);
            $operator = 'NOT LIKE';
        } else {
            $operator = 'LIKE';
        }
        $pattern = sql_escape($pattern);
        $sql =
            "SELECT u.email as email
            FROM {$this->tables['user']} u
            WHERE u.email $operator '%$pattern%'
            ";

        return $this->dbCommand->queryColumn($sql);
    }

    public function allUsers()
    {
        $sql =
            "SELECT email, id, confirmed, blacklisted
            FROM {$this->tables['user']} u
            ";

        return $this->dbCommand->queryAll($sql);
    }

    public function totalUsers()
    {
        $sql =
            "SELECT COUNT(*)
            FROM {$this->tables['user']} u
            ";

        return $this->dbCommand->queryOne($sql);
    }

    public function countInactiveSubscribersByInterval($interval)
    {
        $sql =
            "SELECT COUNT(*)
            FROM (
                SELECT u.id, COUNT(u.id) AS recent_campaigns,
                (SELECT COUNT(*)
                FROM {$this->tables['usermessage']} um
                WHERE um.userid = u.id and status = 'sent'
                ) AS total_campaigns
                FROM {$this->tables['user']} u
                JOIN {$this->tables['usermessage']} um ON um.userid = u.id
                WHERE um.status = 'sent' AND um.entered > DATE_SUB(CURDATE(), INTERVAL $interval)
                AND u.confirmed AND !u.blacklisted
                GROUP BY u.id
                HAVING MAX(um.viewed) IS NULL
                AND recent_campaigns < total_campaigns
            ) AS t1";

        return $this->dbCommand->queryOne($sql);
    }

    public function inactiveSubscribersByInterval($interval, $start = null, $maximum = null)
    {
        $limit = is_null($start) ? '' : "LIMIT $start, $maximum";
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
            AND u.confirmed AND !u.blacklisted
            GROUP BY u.id
            HAVING MAX(um.viewed) IS NULL
            AND recent_campaigns < total_campaigns
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

    public function subscribePages()
    {
        $sql =
            "SELECT id, title
            FROM {$this->tables['subscribepage']}
            ORDER BY id
            ";

        return $this->dbCommand->queryAll($sql);
    }

    public function updateSubscribePage($userId, $pageId)
    {
        $sql =
            "UPDATE {$this->tables['user']}
            SET subscribepage = $pageId
            WHERE id = $userId
            ";

        return $this->dbCommand->queryAffectedRows($sql);
    }

    public function resetBounceCount($userId)
    {
        $sql =
            "UPDATE {$this->tables['user']}
            SET bouncecount = 0
            WHERE id = $userId
            ";

        return $this->dbCommand->queryAffectedRows($sql);
    }

    public function subscribersNoList()
    {
        $sql =
            "SELECT id, email, confirmed, blacklisted
            FROM {$this->tables['user']} u
            LEFT JOIN {$this->tables['listuser']} lu ON u.id = lu.userid
            WHERE userid is NULL
            ";

        return $this->dbCommand->queryAll($sql);
    }

    public function blacklistReasons($start = null, $limit = null)
    {
        $range = is_null($start) ? '' : "LIMIT $start, $limit";
        $sql = <<<END
            SELECT u.id, u.email, ub.added, ubd.data,
            (SELECT GROUP_CONCAT(l.name)
                FROM {$this->tables['list']} l
                JOIN {$this->tables['listuser']} lu ON lu.listid = l.id
                WHERE u.id = lu.userid
                GROUP BY u.id
            ) AS lists
            FROM {$this->tables['user']} u
            JOIN {$this->tables['user_blacklist']} ub ON ub.email = u.email
            JOIN {$this->tables['user_blacklist_data']} ubd ON ubd.email = ub.email AND ubd.name = 'reason'
            WHERE ubd.data != ''
            AND ubd.data != 'Admin Blacklisted'
            AND ubd.data != 'Admin'
            AND ubd.data NOT LIKE '%reason not requested%'
            ORDER BY ub.added DESC
            $range
END;

        return $this->dbCommand->queryAll($sql);
    }

    public function hasBounced($start = null, $limit = null)
    {
        $range = is_null($start) ? '' : "LIMIT $start, $limit";
        $sql = <<<END
            SELECT u.id, u.email, u.confirmed, u.blacklisted, u.bouncecount
            FROM {$this->tables['user']} u
            WHERE u.bouncecount > 0
            ORDER BY u.id
            $range
END;

        return $this->dbCommand->queryAll($sql);
    }

    public function domains()
    {
        $sql = <<<END
            SELECT SUBSTRING_INDEX(email, '@', -1) AS domain,
                COUNT(*) AS total,
                SUM(CASE WHEN confirmed = 1 AND blacklisted = 0 THEN 1 ELSE 0 END) AS active
            FROM {$this->tables['user']}
            GROUP BY domain
            ORDER by total DESC
END;

        return $this->dbCommand->queryAll($sql);
    }

    public function domainSubscribers($domain)
    {
        $domain = sql_escape($domain);
        $sql = <<<END
            SELECT id, email, confirmed, blacklisted
            FROM {$this->tables['user']}
            WHERE SUBSTRING_INDEX(email, '@', -1) = '$domain'
END;

        return $this->dbCommand->queryAll($sql);
    }

    /**
     * Find subscribers who have subscribed but not confirmed.
     * Choose those who have only Subscription or Re-Subscription records in the user history.
     *
     * @return DBResultIterator
     */
    public function notConfirmed()
    {
        $sql = <<<END
            SELECT id, email, entered AS date
            FROM {$this->tables['user']} u
            WHERE id IN (
                -- users with only subscription in user history
                SELECT id
                FROM (
                    -- select users who have only one result row, either only subscriptions or only non-subscriptions
                    SELECT id, MAX(subscription) AS subscription
                    FROM (
                        -- group user history into subscription and non-subscriptions, results in either one or two rows
                        SELECT id, subscription
                        FROM (
                            -- find user history rows within the period
                            SELECT u.id, IF(summary = 'Subscription' OR summary = 'Re-Subscription', 1, 0) AS subscription
                            FROM {$this->tables['user']} u
                            JOIN {$this->tables['user_history']} uh ON u.id = uh.userid
                            WHERE u.confirmed = 0 AND u.blacklisted = 0
                        ) AS t
                        GROUP BY id, subscription
                    ) AS t2
                    GROUP BY id
                    HAVING COUNT(*) = 1
                ) AS t3
                WHERE subscription = 1
            )
END;

        return $this->dbCommand->queryAll($sql);
    }

    public function subscriberHasAttributeValue($userId, $attributeId)
    {
        $sql = <<<END
            SELECT 1
            FROM {$this->tables['user_attribute']}
            WHERE userid = $userId AND attributeid = $attributeId AND COALESCE(value, '') != ''
END;

        return $this->dbCommand->queryOne($sql);
    }
}
