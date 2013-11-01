<?php 
/**
 * SubscribersPlugin for phplist
 * 
 * This file is a part of SubscribersPlugin.
 *
 * @category  phplist
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2011-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */


/**
 * This class is a concrete implementation of CommonPlugin_ControllerFactoryBase
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */
 
class SubscribersPlugin_ControllerFactory extends CommonPlugin_ControllerFactoryBase
{
    protected $defaultType = 'details';

    /**
     * Custom implementation to create a controller using plugin and type
     *
     * @param string $pi the plugin
     * @param array $params further parameters from the URL
     *
     * @return CommonPlugin_Controller 
     * @access public
     */
    public function createController($pi, $params)
    {
		return $this->createControllerType($pi, $params);
	}
}
