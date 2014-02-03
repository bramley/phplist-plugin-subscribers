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
 * @copyright 2011-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This is the entry code invoked by phplist
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */

$commonPlugin = isset($plugins['CommonPlugin']) ? $plugins['CommonPlugin'] : null;

if (!($commonPlugin && $commonPlugin->enabled)) {
    echo "phplist-plugin-common must be installed and enabled to use this plugin";
    return;
}

include $commonPlugin->coderoot . 'Autoloader.php';

CommonPlugin_Main::run(new SubscribersPlugin_ControllerFactory);
