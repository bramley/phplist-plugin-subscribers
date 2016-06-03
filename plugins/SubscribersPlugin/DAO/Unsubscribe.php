<?php

namespace phpList\plugin\SubscribersPlugin\DAO;

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
 * @copyright 2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * DAO class that provides database access for the unsubscribe function.
 */
class Unsubscribe extends User
{
    public function listsForSubscriberMessage($userId, $mId)
    {
        $query = <<<END
SELECT lm.listid, l.name
FROM {$this->tables['listmessage']} lm
JOIN {$this->tables['list']} l ON l.id = lm.listid
JOIN {$this->tables['listuser']} lu ON lu.listid = l.id
WHERE lm.messageid = $mId
AND lu.userid = $userId
END;

        return $this->dbCommand->queryAll($query);
    }

    public function removeSubscriberFromList($userId, $listId)
    {
        $query = <<<END
DELETE FROM {$this->tables['listuser']}
WHERE listid = $listId AND userid = $userId
END;

        return $this->dbCommand->queryAffectedRows($query);
    }

    public function addSubscriberToList($userId, $listId)
    {
        $query = <<<END
INSERT IGNORE INTO {$this->tables['listuser']}
(userid, listid, entered, modified)
VALUES($userId, $listId, now(), now())
END;

        return $this->dbCommand->queryAffectedRows($query);
    }
}
