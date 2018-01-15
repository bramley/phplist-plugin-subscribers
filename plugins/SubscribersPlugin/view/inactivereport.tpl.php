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
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */
?>
<style type="text/css">
input#interval {
    display: inline;
    width: 8em !important;
    margin-left: 10px;
}
form {
    margin-bottom: 0px;
}
</style>
<div>
<?php if (isset($error)) : ?>
    <div class="error"><?= $error; ?></div>
<?php endif; ?>
<?= $toolbar; ?>
    <div style='padding-top: 10px;'>
        <div class="panel">
            <div class="header">
                <h2><?= $this->i18n->get('Enter period of inactivity'); ?></h2>
            </div>
            <div class="content">
                <form class="inline" enctype="multipart/form-data" method='post' action="<?= $formURL; ?>">
                    <label>
<?= $this->i18n->get('Inactivity period'); ?>
<?= $interval ?>
                        <button type="submit" name="submit" value='Process'><?= $this->i18n->get('Process'); ?></button>
                    </label>
                </form>
            </div>
        </div>
    </div>
    <div style='padding-top: 10px;' >
<?= $listing ?>
    </div>
</div>
