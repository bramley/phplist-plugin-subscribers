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
 * @copyright 2011-2019 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\Controller;

use LimitIterator;
use phpList\plugin\Common\Controller;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\Listing;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\Populator;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\SubscribersPlugin\DAO\Command as DAO;

class Domains extends Controller implements IExportable
{
    const TEMPLATE = '/../view/subscriber_report.tpl.php';
    const HELP = 'https://resources.phplist.com/plugin/subscribers?&#subscriber_reports';
    /*
     *  Private variables
     */
    protected $dao;

    /**
     * Displays subscriber domains.
     */
    protected function actionDefault()
    {
        /**
         * Displays the number of subscribers for each domain.
         */
        $domains = $this->dao->domains();
        $populateCallback = function ($w, $start, $limit) use ($domains) {
            $w->setTitle(s('Domain subscriber counts'));
            $w->setElementHeading(s('Domain'));

            foreach (new LimitIterator($domains, $start, $limit) as $row) {
                $key = $row['domain'];
                $w->addElement($key, PageURL::CreateFromGet(['report' => 'domainsubscribers', 'domain' => $row['domain']]));
                $w->addColumn($key, 'Total', $row['total']);
                $w->addColumn($key, 'Active', $row['active']);
            }
        };
        $totalCallback = function () use ($domains) {
            return count($domains);
        };
        $listing = new Listing(new Populator($populateCallback, $totalCallback));
        $toolbar = new Toolbar($this);
        $toolbar->addExportButton();
        $toolbar->addExternalHelpButton(self::HELP);
        $params = [];
        $params['listing'] = $listing->display();
        $params['toolbar'] = $toolbar->display();

        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    public function __construct(DAO $dao)
    {
        parent::__construct();
        $this->dao = $dao;
    }

    /*
     * Implementation of IExportable
     */
    public function exportFileName()
    {
        return 'domains';
    }

    public function exportRows()
    {
        return $this->dao->domains();
    }

    public function exportFieldNames()
    {
        return ['Domain', 'Total', 'Active'];
    }

    public function exportValues(array $row)
    {
        return [$row['domain'], $row['total'], $row['active']];
    }
}
