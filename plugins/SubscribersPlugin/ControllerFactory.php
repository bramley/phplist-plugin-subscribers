<?php
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

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\Container;
use phpList\plugin\Common\ControllerFactoryBase;

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
        global $commandline;

        $depends = include __DIR__ . '/depends.php';
        $container = new Container($depends);
        $page = $params['page'];
        $controller = $page == 'reports' && isset($params['report'])
            ? (in_array($params['report'], ['history', 'subscriptions', 'inactive']) ? $params['report'] : 'simplereport')
            : $page;
        $class = __NAMESPACE__ . '\Controller\\' . ucfirst($controller);

        return $container->get($class);
    }
}
