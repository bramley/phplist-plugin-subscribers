<?php

namespace phpList\plugin\SubscribersPlugin\Model;

use phpList\plugin\Common\Model;
use phpList\plugin\SubscribersPlugin\DAO\Subscriptions as DAOSubscriptions;

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
 * This class.
 */
class Subscriptions extends Model
{
    /*
     *    private variables
     */
    private $dao;

    protected $properties = array(
        'type' => null,
    );
    protected $persist = array(
    );

    /*
     *    private methods
     */
    private function buildSubscriptions($start, $limit)
    {
        $subsIterator = $this->dao->subscriptions($start, $limit);
        $subsIterator->rewind();
        $unsubsIterator = $this->dao->unsubscriptions($start, $limit);
        $unsubsIterator->rewind();
        $subsRow = $subsIterator->valid() ? $subsIterator->current() : array('period' => '999999');
        $unsubsRow = $unsubsIterator->valid() ? $unsubsIterator->current() : array('period' => '999999');
        list($period, $endPeriod) = $this->dao->periodRange($start, $limit);

        $year = substr($period, 0, 4);
        $month = substr($period, 4, 2);
        $result = array();

        while ($period <= $endPeriod) {
            if ($subsRow['period'] == $period) {
                $out = $subsRow;
                $subsIterator->next();

                if ($subsIterator->valid()) {
                    $subsRow = $subsIterator->current();
                }
            } else {
                $out = array(
                    'period' => $period, 'month' => $month, 'year' => $year,
                    'subscriptions' => 0, 'confirmed' => 0, 'unconfirmed' => 0, 'blacklisted' => 0, 'active' => 0,
                );
            }

            if ($unsubsRow['period'] == $period) {
                $out['unsubscriptions'] = $unsubsRow['unsubscriptions'];
                $unsubsIterator->next();

                if ($unsubsIterator->valid()) {
                    $unsubsRow = $unsubsIterator->current();
                }
            } else {
                $out['unsubscriptions'] = 0;
            }
            $result[] = $out;

            if ($month < 12) {
                $month = str_pad(++$month, 2, '0', STR_PAD_LEFT);
            } else {
                $month = '01';
                ++$year;
            }
            $period = $year . $month;
        }

        return $result;
    }

    /*
     *    Public methods
     */
    public function __construct(DAOSubscriptions $dao)
    {
        parent::__construct();
        $this->dao = $dao;
    }

    public function subscriptions($ascendingOrder = true, $start = null, $limit = null)
    {
        $result = $this->buildSubscriptions($start, $limit);

        if (!$ascendingOrder) {
            $result = array_reverse($result);
        }

        return $result;
    }

    public function totalPeriods()
    {
        return $this->dao->totalPeriods();
    }
}
