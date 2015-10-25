<?php

/**
 * SubscribersPlugin for phplist.
 * 
 * This file is a part of SubscribersPlugin.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is a concrete implementation of CommonPlugin_ControllerFactoryBase.
 * 
 * @category  phplist
 */
class SubscribersPlugin_ControllerFactory extends CommonPlugin_ControllerFactoryBase
{
    /**
     * Custom implementation to create a controller using plugin and page.
     *
     * @param string $pi     the plugin
     * @param array  $params further parameters from the URL
     *
     * @return CommonPlugin_Controller
     */
    public function createController($pi, array $params)
    {
        $class = $pi . '_Controller_' . ucfirst($params['page']);

        return new $class();
    }
}
