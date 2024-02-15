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
 * @copyright 2017-2020 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\DAO;

use phpList\plugin\Common\DAO as CommonDAO;
use phpList\plugin\Common\DAO\ListsTrait;
use phpList\plugin\Common\DAO\ListUserTrait;
use phpList\plugin\Common\DAO\UserTrait;

/**
 * DAO class that provides database access for the unsubscribe function.
 */
class ListSubscription extends CommonDAO
{
    use ListsTrait;
    use ListUserTrait;
    use UserTrait;

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
}
