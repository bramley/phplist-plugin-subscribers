<?php
/**
 * SubscribersPlugin for phplist.
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
 * @author    Duncan Cameron
 * @copyright 2011-2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */
?>
<div>
    <hr/>
<?= $toolbar; ?>
    <div style='padding-top: 10px;' >
        <div class="panel">
            <div class="header"><h2><?= $this->i18n->get('Subscribers with an invalid email address'); ?></h2></div>
            <div class="content">
<?= $listing ?>
                <br>
<?= $cancel; ?>
            </div>
        </div>
    </div>
</div>
