<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\ControllerFactoryBase;

/**
 * SubscribersPlugin for phplist.
 *
 * This file is a part of SubscribersPlugin.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class is a concrete implementation of ControllerFactoryBase.
 *
 * @category  phplist
 */
class ControllerFactory extends ControllerFactoryBase
{
    /**
     * Custom implementation to create a controller using plugin and page.
     * The controller is created by the dependency injection container.
     *
     * @param string $pi     the plugin
     * @param array  $params further parameters from the URL
     *
     * @return \phpList\plugin\Common\Controller
     */
    public function createController($pi, array $params)
    {
        $depends = include __DIR__ . '/depends.php';
        $container = new \phpList\plugin\Common\Container($depends);
        $class = 'phpList\plugin\\' . $pi . '\\Controller\\' . ucfirst($params['page']);

        return $container->get($class);
    }
}
