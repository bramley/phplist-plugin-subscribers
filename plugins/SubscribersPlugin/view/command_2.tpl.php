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
div inline {
    display: inline;
    white-space: nowrap
}
.inline label{
    display: inline;
}
input[type="text"], select {
    width: auto !important;
    display: inline !important;
}
</style>

<div>
    <hr/>
<?= $toolbar; ?>
    <div style='padding-top: 10px;' >
        <div class="panel">
            <div class="header">
                <h2>
<?= $this->i18n->get('Confirm action and subscribers'); ?>
                </h2>
            </div>
            <div class="content">
                <form class="inline" enctype="multipart/form-data" method='post' action="<?= $formURL; ?>">
                    <div class="note">
<?= $this->i18n->get('Review the action and the email addresses, then click Apply or Cancel.'); ?>
                    </div>
<?= $this->i18n->get('Action for each subscriber'); ?>
                    : <br />
<?= $commandList; ?>
                    &nbsp;
<?= $listSelect;?>
<?= $userArea; ?>
                    <br>
<?= $cancel; ?>
                    <button type="submit" name="submit" value='Apply'><?= $this->i18n->get('Apply'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
