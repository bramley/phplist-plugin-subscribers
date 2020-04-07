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
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\Report;

/**
 * {@inheritdoc}
 */
class Unsubscribereason extends AbstractReport
{
    public function getIterator($dao)
    {
        return $dao->blacklistReasons();
    }

    public function title()
    {
        return $this->i18n->get('Unsubscribe reasons');
    }

    public function noSubscribersWarning()
    {
        return $this->i18n->get('No unsubscribe reasons found');
    }

    public function columnCallback()
    {
        return function () {
            return [
                $this->i18n->get('date'),
                $this->i18n->get('reason'),
                $this->i18n->get('lists'),
            ];
        };
    }

    public function valuesCallback()
    {
        return function ($row) {
            return [
                $row['added'],
                $row['data'],
                $row['lists'],
            ];
        };
    }
}
