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

namespace phpList\plugin\SubscribersPlugin\Controller;

use phpList\plugin\Common\Controller;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\Listing;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\SubscribersPlugin\DAO\Command as DAO;
use phpList\plugin\SubscribersPlugin\ReportFactory;
use phpList\plugin\SubscribersPlugin\SubscriberPopulator;

class Simplereport extends Controller
{
    const PLUGIN = 'SubscribersPlugin';
    const TEMPLATE = '/../view/subscriber_report.tpl.php';
    const HELP = 'https://resources.phplist.com/plugin/subscribers?&#subscriber_reports';

    private $iterator;
    private $populator;
    private $report;

    protected function actionDefault()
    {
        $listing = new Listing($this, $this->populator);
        $toolbar = new Toolbar($this);
        $toolbar->addExportButton();
        $toolbar->addExternalHelpButton(self::HELP);

        $params = [];
        $params['listing'] = $listing->display();
        $params['toolbar'] = $toolbar->display();

        if (count($this->iterator) == 0) {
            $params['warning'] = $this->report->noSubscribersWarning();
        }
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    protected function actionExportCSV(IExportable $exportable = null)
    {
        parent::actionExportCSV($this->populator);
    }

    public function __construct($reportId, ReportFactory $factory, DAO $dao)
    {
        parent::__construct();

        $this->report = $factory->create($reportId);
        $this->iterator = $this->report->iterator($dao);
        $this->populator = new SubscriberPopulator(
            $this->i18n,
            $this->iterator,
            $this->report->title(),
            $this->report->columnCallback(),
            $this->report->valuesCallback()
        );
    }
}
