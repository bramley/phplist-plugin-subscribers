<?php

namespace phpList\plugin\SubscribersPlugin\Controller;

use CHtml;
use phpList\plugin\Common\Controller;
use phpList\plugin\Common\DB;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\IPopulator;
use phpList\plugin\Common\Listing;
use phpList\plugin\Common\PageLink;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\Common\WebblerListing;
use phpList\plugin\SubscribersPlugin\Model\Subscriptions as Model;

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
 * @copyright 2012-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is the controller for the plugin providing the action methods.
 * 
 * @category  phplist
 */
class Subscriptions
    extends Controller
    implements IPopulator, IExportable
{
    const TEMPLATE = '/../view/subscriptions.tpl.php';
    const IMAGE_HEIGHT = 300;

    private $subscriptions;

    protected $model;

    /*
     *    Private methods
     */
    private function addRow(WebblerListing $w, array $row)
    {
        $key = "{$row['year']} {$row['month']}";
        $w->addElement($key);
        $w->addColumn($key, $this->i18n->get('subscriptions'), $row['subscriptions']);

        $format = '%2$d (%1$d%%)';
        $w->addColumn($key, $this->i18n->get('active'),
            sprintf($format,
                $row['subscriptions'] == 0 ? 0 : round($row['active'] / $row['subscriptions'] * 100),
                $row['active'])
        );
        $w->addColumn($key, $this->i18n->get('blacklisted'),
            sprintf($format,
                $row['subscriptions'] == 0 ? 0 : round($row['blacklisted'] / $row['subscriptions'] * 100),
                $row['blacklisted'])
        );
        $w->addColumn($key, $this->i18n->get('unconfirmed'),
            sprintf($format,
                $row['subscriptions'] == 0 ? 0 : round($row['unconfirmed'] / $row['subscriptions'] * 100),
                $row['unconfirmed'])
        );
        $w->addColumn($key, $this->i18n->get('unsubscriptions'), $row['unsubscriptions']);
    }

    private function createChart($chartDiv)
    {
        $currentYear = '';
        $data = array();

        foreach ($this->subscriptions as $k => $row) {
            $monthLabel = '';

            if ($row['month'] % 3 == 1 || $k == 0 || $k == count($this->subscriptions) - 1) {
                $monthLabel = $row['month'];

                if ($currentYear != $row['year']) {
                    $monthLabel = $row['year'] . ' ' . $monthLabel;
                }
            }

            $data[] = array(
                'month' => $monthLabel,
                $this->i18n->get('Active') => (int) $row['active'],
                $this->i18n->get('Blacklisted') => (int) $row['blacklisted'],
                $this->i18n->get('Unconfirmed') => (int) $row['unconfirmed'],
                $this->i18n->get('Unsubscriptions') => (int) $row['unsubscriptions'],
            );

            $currentYear = $row['year'];
        }
        $chart = new \Chart('ComboChart');
        $chart->load($data, 'array');
        $options = array(
            'chartArea' => array('left' => 50, 'width' => '90%'),
            'height' => self::IMAGE_HEIGHT,
            'axisTitlesPosition' => 'out',
            'vAxis' => array('format' => '#', 'title' => $this->i18n->get('Subscribers')),
            'hAxis' => array('title' => $this->i18n->get('Period'), 'textStyle' => array('fontSize' => 9)),
            'bar' => array('groupWidth' => '90%'),
            'seriesType' => 'bars',
            'series' => array(3 => array('type' => 'line')),
            'legend' => array('position' => 'bottom'),
            'isStacked' => true,
        );
        $result = $chart->draw($chartDiv, $options);

        return $result;
    }

    /*
     *    Protected methods
     */
    protected function actionDefault()
    {
        global $google_chart_direct;

        $params = array();
        $toolbar = new Toolbar($this);
        $toolbar->addExportButton();
        $toolbar->addHelpButton('subscriptions');
        $params['toolbar'] = $toolbar->display();

        $listing = new Listing($this, $this);
        $listing->sort = false;
        $listing->pager->setItemsPerPage(array(12, 24), 24);
        $params['listing'] = $listing->display();

        $params['chart_div'] = 'chart_div';
        $params['chart'] = $this->createChart($params['chart_div']);
        echo $this->render(dirname(__FILE__) . self::TEMPLATE, $params);
    }

    /*
     *    Public methods
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model(new DB());
        $this->model->setProperties($_GET);
    }
    /*
     * Implementation of IPopulator
     */
    public function populate(WebblerListing $w, $start, $limit)
    {
        /*
         * Populates the webbler list with subscription details
         */
        $w->setTitle($this->i18n->get('period'));

        $sumSubscriptions = $sumConfirmed = $sumUnconfirmed = $sumBlacklisted = $sumActive = $sumUnsubscriptions = 0;
        $rows = $this->model->subscriptions(false, $start, $limit);
        $this->subscriptions = array_reverse($rows);

        foreach ($rows as $row) {
            $sumSubscriptions += $row['subscriptions'];
            $sumConfirmed += $row['confirmed'];
            $sumUnconfirmed += $row['unconfirmed'];
            $sumBlacklisted += $row['blacklisted'];
            $sumActive += $row['active'];
            $sumUnsubscriptions += $row['unsubscriptions'];
            $this->addRow($w, $row);
        }
        $this->addRow($w, array(
            'year' => 'Total',
            'month' => '',
            'subscriptions' => $sumSubscriptions,
            'unsubscriptions' => $sumUnsubscriptions,
            'confirmed' => $sumConfirmed,
            'unconfirmed' => $sumUnconfirmed,
            'blacklisted' => $sumBlacklisted,
            'active' => $sumActive,
        ));
    }

    public function total()
    {
        return $this->model->totalPeriods();
    }

    /*
     * Implementation of IExportable
     */
    public function exportFileName()
    {
        return 'subscriptions';
    }

    public function exportRows()
    {
        return $this->model->subscriptions();
    }

    public function exportFieldNames()
    {
        return $this->i18n->get(array(
            'year', 'month', 'subscriptions', 'active', 'blacklisted', 'unconfirmed', 'unsubscriptions',
        ));
    }

    public function exportValues(array $row)
    {
        return array(
            $row['year'],
            $row['month'],
            $row['subscriptions'],
            $row['active'],
            $row['blacklisted'],
            $row['unconfirmed'],
            $row['unsubscriptions'],
        );
    }
}
