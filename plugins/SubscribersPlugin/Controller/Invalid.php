<?php

namespace phpList\plugin\SubscribersPlugin\Controller;

use phpList\plugin\Common\Controller;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\Listing;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\SubscribersPlugin\InvalidPopulator;
use phpList\plugin\SubscribersPlugin\DAO\Command as DAO;

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

/**
 * This class is the controller for the plugin providing the action methods.
 */
class Invalid extends Controller
{
    const PLUGIN = 'SubscribersPlugin';
    const TEMPLATE = '/../view/invalidreport.tpl.php';
    const HELP = 'https://resources.phplist.com/plugin/subscribers?&#subscriber_reports';

    protected $dao;

    /**
     * Validates the email address of each subscriber and displays those that are invalid.
     */
    protected function actionDefault()
    {
        $invalid = [];
        $params = [];

        foreach ($this->dao->allUsers() as $row) {
            if (!is_email($row['email'])) {
                $invalid[] = $row;
            }
        }

        if (count($invalid) == 0) {
            $params['warning'] = $this->i18n->get('All subscribers have a valid email address');
        }
        $populator = new InvalidPopulator($this->i18n, $invalid);
        $listing = new Listing($this, $populator);
        $toolbar = new Toolbar($this);
        $toolbar->addExportButton();
        $toolbar->addExternalHelpButton(self::HELP);
        $_SESSION[self::PLUGIN]['invalid'] = $invalid;

        $params['listing'] = $listing->display();
        $params['toolbar'] = $toolbar->display();
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    protected function actionExportCSV(IExportable $exportable = null)
    {
        $populator = new InvalidPopulator($this->i18n, $_SESSION[self::PLUGIN]['invalid']);
        parent::actionExportCSV($populator);
    }

    public function __construct(DAO $dao)
    {
        parent::__construct();
        $this->dao = $dao;
    }
}
