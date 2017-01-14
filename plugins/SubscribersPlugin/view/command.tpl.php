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
<?php if (isset($result)) : ?>
    <div style='padding-top: 10px;'>
        <div class="result"><?= $result; ?></div>
    </div>
<?php endif; ?>
<?php if (isset($error)) : ?>
    <div style='padding-top: 10px;'>
        <div class="error"><?= $error; ?></div>
    </div>
<?php endif; ?>
    <div style='padding-top: 10px;' >
        <div class="panel">
            <div class="header"><h2><?= $this->i18n->get('Apply command to a group of subscribers'); ?></h2></div>
            <div class="content">
                <form class="inline" enctype="multipart/form-data" method='post' action="<?= $formURL; ?>">
<?= $this->i18n->get('Action for each subscriber'); ?>
                    : <br />
<?= $commandList; ?>
                    &nbsp;
<?= $listSelect;?>
                    <div class="note">
<?= $this->i18n->get('Copy/paste a list of email addresses, then click the Process button'); ?>
                    </div>
                    <textarea name='emails' rows='5' cols='30' ></textarea>
                    <button type="submit" name="submit" value='Process'><?= $this->i18n->get('Process'); ?></button>
                    <div class="note">
<?= $this->i18n->get('Or select a file of email addresses to upload, then click the Upload button'); ?>
                    </div>
                    <input type="file" name="file" value="upload" size='48'/>
                    <button type="submit" name="submit" value='Upload'><?= $this->i18n->get('Upload'); ?></button>
                    <div class="note">
<?= $this->i18n->get('Or enter a partial email address to be matched, then click the Match button'); ?>
                    </div>
                    <input type='text' name='pattern' size='16' value="" />
                    <button type="submit" name="submit" value='Match'><?= $this->i18n->get('Match'); ?></button>
                </form>
            </div>
        </div>
    </div>
    <div style='padding-top: 10px;' >
        <div class="panel">
            <div class="header"><h2><?= $this->i18n->get('Validate subscriber email addresses'); ?></h2></div>
            <div class="content">
                <p><?= $this->i18n->get('Show subscribers who have an invalid email address'); ?>
                <a class="button" href="<?= $validateURL; ?>"><?= $this->i18n->get('Validate'); ?></a></p>
            </div>
        </div>
    </div>
</div>
