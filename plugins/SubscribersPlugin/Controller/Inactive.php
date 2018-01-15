<?php

namespace phpList\plugin\SubscribersPlugin\Controller;

use CHtml;
use phpList\plugin\Common\Controller;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\Listing;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\SubscribersPlugin\InactivePopulator;
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
class Inactive extends Controller
{
    const PLUGIN = 'SubscribersPlugin';
    const TEMPLATE = '/../view/inactivereport.tpl.php';
    const HELP = 'https://resources.phplist.com/plugin/subscribers?&#subscriber_reports';
    /*
     *  Private variables
     */
    protected $dao;

    /**
     * Saves variables into the session then redirects and exits.
     *
     * @param string $redirect the redirect location
     * @param array  $session  variables to be stored in the session
     */
    private function redirectExit($redirect, array $session = array())
    {
        $_SESSION[self::PLUGIN] = $session;
        header('Location: ' . $redirect);
        exit;
    }

    /**
     * Displays inactive subscribers.
     */
    protected function actionDefault()
    {
        $params = [];

        if (isset($_POST['interval'])) {
            $interval = $_POST['interval'];

            if (preg_match('/^(\d+\s+(day|week|month|quarter|year))s?$/i', $interval, $matches)) {
                $this->redirectExit(PageURL::createFromGet(['interval' => $matches[1]]));
            }
            $this->redirectExit(PageURL::createFromGet(), ['error' => $this->i18n->get("Invalid interval value '%s'", $interval)]);
        }

        if (isset($_SESSION[self::PLUGIN]['error'])) {
            $params['error'] = $_SESSION[self::PLUGIN]['error'];
            unset($_SESSION[self::PLUGIN]['error']);
        }

        $interval = isset($_GET['interval']) ? $_GET['interval'] : '6 month';
        $populator = new InactivePopulator($this->dao, $this->i18n, $interval);
        $listing = new Listing($this, $populator);
        $listing->pager->setItemsPerPage([25, 50, 100], 25);
        $toolbar = new Toolbar($this);
        $toolbar->addExportButton(['interval' => $interval, 'report' => $_GET['report']]);
        $toolbar->addExternalHelpButton(self::HELP);

        $params['listing'] = $listing->display();
        $params['toolbar'] = $toolbar->display();
        $params['interval'] = CHtml::textField('interval', $interval, ['id' => 'interval']);
        $params['formURL'] = PageURL::createFromGet();

        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    protected function actionExportCSV(IExportable $exportable = null)
    {
        $populator = new InactivePopulator($this->dao, $this->i18n, $_GET['interval']);
        parent::actionExportCSV($populator);
    }

    public function __construct(DAO $dao)
    {
        parent::__construct();
        $this->dao = $dao;
    }
}
