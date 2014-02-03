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
 * @copyright 2011 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This is the HTML template for the plugin page
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */

/**
 *
 * Available fields
 * - toolbar: raw HTML output of CommonPlugin_Toolbar
 * - tabs: raw HTML output of CommonPlugin_Tabs
 * - message: exception message
 * - panel: raw HTML output of UIPanel
 * - listing: raw HTML output of CommonPlugin_Listing
 */
?>
<div >
    <hr/>
<?php echo $toolbar; ?>
    <div style='padding-top: 10px;'>
<?php if (isset($message)) echo $message; ?>
<?php echo $panel; ?>
    </div>
    <div style='padding-top: 10px;'>
<?php if (isset($listing)) echo $listing; ?>
        <p><a href='#top'>[<?php echo $this->i18n->get('top'); ?>]</a></p>
    </div>
</div>