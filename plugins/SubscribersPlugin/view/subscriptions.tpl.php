<?php

namespace phpList\plugin\SubscribersPlugin;

/**
 * SubscribersPlugin for phplist.
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
 *
 * @author    Duncan Cameron
 * @copyright 2012-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This is the HTML template for the plugin page.
 */

/**
 * Available fields
 * - chart: optional chart
 * - listing: optional HTML output of Listing.
 */
?>
<div >
<?php echo $toolbar; ?>
    <div style='padding-top: 10px;'>
<?php if (isset($chart)): ?>
    <?php echo $chart; ?>
        <div id="<?php echo $chart_div; ?>" style="width: 100%; height: <?php echo Controller\Subscriptions::IMAGE_HEIGHT; ?>px;"></div>
<?php endif; ?>
    </div>
    <div style='padding-top: 10px;'>
<?php if (isset($listing)) {
    echo $listing;
} ?>
    </div>
    <p><a href='#top'>[<?php echo s('top'); ?>]</a></p>
</div>
