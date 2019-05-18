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

namespace phpList\plugin\SubscribersPlugin;

class ReportFactory
{
    private $reports = [
        'history' => ['params' => ['report' => 'history'], 'caption' => 'Subscriber history'],
        'subscriptions' => ['params' => ['report' => 'subscriptions'], 'caption' => 'Subscriptions'],
        'invalid' => ['params' => ['report' => 'invalid'], 'caption' => 'Subscribers with an invalid email address'],
        'inactive' => ['params' => ['report' => 'inactive'], 'caption' => 'Inactive subscribers'],
        'nolist' => ['params' => ['report' => 'nolist'], 'caption' => 'Subscribers who do not belong to a list'],
        'unsubscribereason' => ['params' => ['report' => 'unsubscribereason'], 'caption' => 'Unsubscribe reasons'],
        'bounce' => ['params' => ['report' => 'bounce'], 'caption' => 'Bounce count'],
        'domains' => ['params' => ['report' => 'domains'], 'caption' => 'Domain subscriber counts'],
    ];

    public function listReports()
    {
        return $this->reports;
    }

    public function create($reportId)
    {
        $class = __NAMESPACE__ . '\Report\\' . ucfirst($reportId);

        if (!class_exists($class)) {
            throw new \Exception("report $reportId not found");
        }

        return new $class();
    }
}
