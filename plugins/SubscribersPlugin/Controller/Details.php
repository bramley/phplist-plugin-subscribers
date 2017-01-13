<?php

namespace phpList\plugin\SubscribersPlugin\Controller;

use phpList\plugin\Common\Controller;
use phpList\plugin\Common\DB;
use phpList\plugin\Common\IPopulator;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\ImageTag;
use phpList\plugin\Common\Listing;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\Toolbar;
use phpList\plugin\Common\WebblerListing;
use phpList\plugin\Common\Widget;
use phpList\plugin\SubscribersPlugin\Model\Details as Model;

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
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is the controller for the plugin providing the action methods
 * Implements the IPopulator and IExportable interfaces.
 * 
 * @category  phplist
 */
class Details
    extends Controller
    implements IPopulator, IExportable
{
    const TEMPLATE = '/../view/details.tpl.php';
    /*
     *    Protected attributes
     */
    protected $model;
    /*
     *    Protected methods
     */
    protected function actionDefault()
    {
        if (isset($_POST['SearchForm'])) {
            $this->normalise($_POST['SearchForm']);
            $this->model->setProperties($_POST['SearchForm'], true);
            $redirect = new PageURL();
            header("Location: $redirect");
            exit;
        }

        $toolbar = new Toolbar($this);
        $toolbar->addExportButton();
        $toolbar->addHelpButton('details');
        $listing = new Listing($this, $this);
        $params = array(
            'toolbar' => $toolbar->display(),
            'form' => Widget::attributeForm($this, $this->model),
            'listing' => $listing->display(),
        );
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
     * Implementation of IExportable
     */
    public function exportFileName()
    {
        return 'subscribers';
    }

    public function exportRows()
    {
        return $this->model->users();
    }

    public function exportFieldNames()
    {
        $result = array();
        $result[] = $this->i18n->get('id');
        $result[] = $this->i18n->get('email');
        $result[] = $this->i18n->get('confirmed_heading');
        $result[] = $this->i18n->get('blacklisted_heading');

        foreach ($this->model->selectedAttrs as $attr) {
            $result[] = $this->model->attributes[$attr]['name'];
        }
        $result[] = $this->i18n->get('HTML');
        $result[] = $this->i18n->get('Lists');
        $result[] = $this->i18n->get('Campaigns sent');
        $result[] = $this->i18n->get('Campaigns opened');
        $result[] = $this->i18n->get('Campaigns clicked');

        return $result;
    }

    public function exportValues(array $row)
    {
        $result = array();
        $result[] = $row['id'];
        $result[] = $row['email'];
        $result[] = $row['confirmed'];
        $result[] = $row['blacklisted'];

        foreach ($this->model->selectedAttrs as $attr) {
            $result[] = $row["attr{$attr}"];
        }
        $result[] = $row['htmlemail'];
        $result[] = $row['lists'];
        $result[] = $row['sent'];
        $result[] = $row['opens'];
        $result[] = $row['clicks'];

        return $result;
    }
    /*
     * Implementation of IPopulator
     */
    public function populate(WebblerListing $w, $start, $limit)
    {
        /*
         * Populates the webbler list with users details
         */
        $selectedAttrs = $this->model->selectedAttrs;
        $attributes = $this->model->attributes;
        $w->setTitle($this->i18n->get('Subscribers'));

        foreach ($this->model->users($start, $limit) as $row) {
            $key = $row['email'];
            $w->addElement($key, new PageURL('user', array('id' => $row['id'])));

            $value = $row['confirmed']
                ? ''
                : new ImageTag('no.png', $this->i18n->get('not confirmed'));
            $w->addColumnHtml($key, $this->i18n->get('confirmed_heading'), $value);

            $value = $row['blacklisted']
                ? new ImageTag('user.png', $this->i18n->get('User is blacklisted'))
                : '';
            $w->addColumnHtml($key, $this->i18n->get('blacklisted_heading'), $value);

            foreach ($selectedAttrs as $attr) {
                $w->addColumn($key, $attributes[$attr]['name'], $row["attr{$attr}"]);
            }
            $w->addColumn($key, $this->i18n->get('HTML'), $row['htmlemail']);
            $w->addColumn($key, $this->i18n->get('Lists'), $row['lists']);
            $w->addColumn($key, $this->i18n->get('Campaigns'), "{$row['sent']} | {$row['opens']} | {$row['clicks']}");
        }
    }

    public function total()
    {
        return $this->model->totalUsers();
    }
}
