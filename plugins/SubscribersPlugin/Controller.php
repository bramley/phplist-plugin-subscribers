<?php
/**
 * SubscribersPlugin for phplist
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
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2012-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This is a base class that provides common processing for each controller
 */
abstract class SubscribersPlugin_Controller extends CommonPlugin_Controller
{
	/*
	 *	Protected methods
	 */
	protected function tabs($current)
	{
        $pages = array(
            'details' => $this->i18n->get('tab_details'),
            'history' => $this->i18n->get('tab_history'),
            'subscriptions' => $this->i18n->get('tab_subscriptions')
        );
		$tabs = new CommonPlugin_Tabs();

		foreach ($pages as $page => $caption) {
			$tabs->addTab($caption, new CommonPlugin_PageURL(null, array('type' => $page)));
		}
		$tabs->setCurrent($pages[$current]);

		return $tabs;
	}

    public function __construct()
    {
        parent::__construct();
    }
}
