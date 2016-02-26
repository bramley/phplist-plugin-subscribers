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
 * @copyright 2011-2013 Duncan Cameron
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
<?php echo $toolbar; ?>
<?php if (isset($result)) : ?>
    <div style='padding-top: 10px;'>
        <div class="note"><?php echo $result; ?></div>
    </div>
<?php endif; ?>
<?php if (isset($error)) : ?>
    <div style='padding-top: 10px;'>
        <div class="error"><?php echo $error; ?></div>
    </div>
<?php endif; ?>
    <div style='padding-top: 10px;' >
        <div class="panel">
            <div class="header"><h2><?php echo $panelTitle; ?></h2></div>
            <div class="content">
                <form class="inline" enctype="multipart/form-data" method='post' action="<?php echo $formURL; ?>">
<?php if (isset($userArea)): ?>
                    <div class="note"><?php echo $this->i18n->get('Review the action and the email addresses, then click Apply or Cancel.'); ?></div>
                    <?php echo $this->i18n->get('Action for each subscriber'); ?>: <br />
                    <?php echo $updateList; ?>&nbsp;<?php echo $listSelect;?>
                    <?php echo $userArea; ?>
                    <br><input type="submit" name="action" value="Apply" />
                    <?php echo $cancel; ?>
<?php else: ?>
                    <?php echo $this->i18n->get('Action for each subscriber'); ?>: <br />
                    <?php echo $updateList; ?>&nbsp;<?php echo $listSelect;?>
                    <p><?php echo $this->i18n->get('Select file to upload, then click the Upload button'); ?></p>
                    <input type="file" name="file" value="upload" size='48'/>
                    <input type="submit" name="submit" value="Upload" />
                    <p><?php echo $this->i18n->get('or enter partial email address to be matched and click the Match button'); ?></p>
                    <input type='text' name='pattern' size='16' value="" />
                    <br><input type="submit" name="submit" value="Match" />
<?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
