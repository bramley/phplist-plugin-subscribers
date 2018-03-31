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

abstract class AbstractReport
{
    /**
     * Implemented by a subclass to provide an iterator of the subscriber query result.
     *
     * @param phpList\plugin\SubscribersPlugin\DAO\Command $dao
     *
     * @return Iterator iterator that provides the query results. Also must be Countable.
     */
    abstract public function iterator($dao);

    /**
     * This method can be overridden to provide a callback that returns additional columns for the report listing.
     *
     * @return callable|null
     */
    public function columnCallback()
    {
        return null;
    }

    /**
     * This method can be overridden to provide a callback that returns the values of the additional columns.
     *
     * @return callable|null
     */
    public function valuesCallback()
    {
        return null;
    }

    /**
     * Returns the text to be used as the title of the report.
     *
     * @return string
     */
    public function title()
    {
        return '';
    }

    /**
     * Returns a warning message to be displayed when there are no subscribes to be displayed.
     *
     * @return string
     */
    public function noSubscribersWarning()
    {
        return '';
    }
}
