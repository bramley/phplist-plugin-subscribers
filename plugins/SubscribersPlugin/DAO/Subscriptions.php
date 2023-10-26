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
 * @copyright 2012-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * DAO class providing database queries.
 */
class Subscriptions extends DAO
{
    /**
     * The number of unsubscriptions for each month in the period range.
     *
     * @param int $start start month of the range, offset from current month
     * @param int $limit the number of months in the range
     *
     * @return array
     */
    public function unsubscriptions($start, $limit)
    {
        $periodRange = is_null($start)
            ? ''
            : "AND PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM now()), EXTRACT(YEAR_MONTH FROM uh.date)) BETWEEN $start AND $start + $limit - 1";

        $sql = "
            SELECT EXTRACT(YEAR_MONTH FROM uh.date) AS period, COUNT(uh.date) as unsubscriptions
            FROM {$this->tables['user_history']} uh
            WHERE summary = 'Unsubscription'
            $periodRange
            GROUP BY period
            ORDER BY period";

        return $this->dbCommand->queryAll($sql);
    }

    /**
     * Derives for each month in the period range
     * - number of subscriptions
     * - number of confirmed subscriptions
     * - number of unconfirmed subscriptions
     * - number of confirmed subscriptions that are now blacklisted
     * - number of confirmed subscriptions that are not blacklisted.
     *
     * @param int $start start month of the range, offset from current month
     * @param int $limit the number of months in the range
     *
     * @return array
     */
    public function subscriptions($start, $limit)
    {
        $periodRange = is_null($start)
            ? ''
            : "AND PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM now()), EXTRACT(YEAR_MONTH FROM entered)) BETWEEN $start AND $start + $limit - 1";

        $sql = "
            SELECT YEAR(entered) AS year,
            LPAD(MONTH(entered), 2, '0') AS month,
            EXTRACT(YEAR_MONTH FROM entered) AS period,
            SUM(1) AS subscriptions,
            SUM(CASE WHEN blacklisted = 0 AND confirmed = 1 THEN 1 ELSE 0 END) AS active,
            SUM(CASE WHEN blacklisted = 0 AND confirmed = 0 THEN 1 ELSE 0 END) AS unconfirmed,
            SUM(CASE WHEN blacklisted = 1 THEN 1 ELSE 0 END) AS blacklisted
            FROM {$this->tables['user']} u
            WHERE YEAR(entered) > 0
            $periodRange
            GROUP BY period, year, month
            ORDER BY period
            ";

        return $this->dbCommand->queryAll($sql);
    }

    /**
     * Calculates the number of months since the first subscription inclusive of the
     * current month.
     *
     * @return int
     */
    public function totalPeriods()
    {
        $sql = "
            SELECT 1 + PERIOD_DIFF(
                EXTRACT(YEAR_MONTH FROM now()),
                COALESCE(EXTRACT(YEAR_MONTH FROM min(entered)), EXTRACT(YEAR_MONTH FROM now()))
            ) AS t
            FROM {$this->tables['user']}
            WHERE YEAR(entered) > 0";

        return $this->dbCommand->queryOne($sql);
    }

    /**
     * Calculates the first and last period for the range
     * periods are in the format YYYYMM.
     *
     * @param int $start start month of the range, offset from current month
     * @param int $limit the number of months in the range
     *
     * @return array
     */
    public function periodRange($start, $limit)
    {
        $totalPeriods = $this->totalPeriods();

        if (is_null($start)) {
            $latest = 0;
            $earliest = $totalPeriods;
        } else {
            $latest = $start == 0 ? 0 : (0 - $start);
            $earliest = $start + $limit;

            if ($totalPeriods < $earliest) {
                $earliest = $totalPeriods;
            }
        }
        $earliest = (0 - $earliest) + 1;
        $sql = "SELECT
                PERIOD_ADD(EXTRACT(YEAR_MONTH FROM now()), $latest) AS end,
                PERIOD_ADD(EXTRACT(YEAR_MONTH FROM now()), $earliest) AS start";

        $row = $this->dbCommand->queryRow($sql);

        return array($row['start'], $row['end']);
    }
}
