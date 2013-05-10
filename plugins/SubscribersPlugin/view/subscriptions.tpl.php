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
 * This is the HTML template for the plugin page
 * 
 */

/**
 *
 * Available fields
 * - chartURL: optional
 * - chartMessage: optional chart exception message
 * - listing: optional HTML output of CommonPlugin_Listing
 */
?>
<div >
    <hr/>
<?php echo $toolbar; ?>
    <div style='padding-top: 10px;'>
<?php if (isset($chartURL)): ?>
        <img src='<?php echo htmlspecialchars($chartURL); ?>' width='600'  height='300' />
<?php endif; ?>
<?php if (isset($chartMessage)): ?>
        <p><?php echo $chartMessage; ?></p>
<?php endif; ?>
    <div style='padding-top: 10px;'>
<?php if (isset($listing)) echo $listing; ?>
    </div>
        <p><a href='#top'>[<?php echo $this->i18n->get('top'); ?>]</a></p>
    </div>
</div>