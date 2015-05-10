<?php
/**
 * SubscribersPlugin for phplist
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
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2011-2014 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * Registers the plugin with phplist
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */
class SubscribersPlugin extends phplistPlugin
{
    const VERSION_FILE = 'version.txt';

    /*
     *  Inherited variables
     */
    public $name = 'Subscribers Plugin';
    public $enabled = true;
    public $authors = 'Duncan Cameron';
    public $description = 'Provides pages to display subscriber attributes, subscriber history, and subscriptions.';
    public $topMenuLinks = array(
        'details' => array('category' => 'subscribers'),
        'history' => array('category' => 'subscribers'),
        'subscriptions' => array('category' => 'subscribers')
    );
    public $pageTitles = array(
        'details' => 'Advanced search',
        'history' => 'Subscriber History',
        'subscriptions' => 'Subscriptions',
    );

    public function adminmenu()
    {
        return $this->pageTitles;
    }

    public function __construct()
    {
        $this->coderoot = dirname(__FILE__) . '/SubscribersPlugin/';
        $this->version = (is_file($f = $this->coderoot . self::VERSION_FILE))
            ? file_get_contents($f)
            : '';
        parent::__construct();
    }

    public function dependencyCheck()
    {
        global $plugins;

        return array(
            'Common plugin installed' =>
                phpListPlugin::isEnabled('CommonPlugin') && 
                (substr($plugins['CommonPlugin']->version, 0, 3) === 'Git' || $plugins['CommonPlugin']->version >= '2015-03-23'),
            'PHP version 5.3.0 or greater' => version_compare(PHP_VERSION, '5.3') > 0,
        );
    }
}
