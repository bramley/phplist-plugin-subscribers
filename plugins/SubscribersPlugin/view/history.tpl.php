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
 * - listing: raw HTML output of CommonPlugin_Listing
 * - message: exception message
 * - model: SubscribersPlugin_Model
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
<div >
    <hr/>
<?php echo $toolbar; ?>
    <div style='padding-top: 10px;'>
<?php if (isset($message)) echo $message; ?>
        <form method='POST' class='inline'>
            <fieldset>
                <legend><?php echo $this->i18n->get('Show events'); ?></legend>
                <label>
                    <input type='radio' name='ShowForm[option]' value='all' 
                    <?php if ($model->option == 'all') echo "checked='checked'" ?> />
                    <?php echo $this->i18n->get('All'); ?>
                </label>
                <label>
                    <input type='radio' name='ShowForm[option]' value='date'  id='radioDate'
                    <?php if ($model->option == 'date') echo "checked='checked'" ?> />
                    <?php echo $this->i18n->get('Since'); ?>
                </label>
                &nbsp;
                <input type='text' name='ShowForm[from]' size='10' title='yyyy-mm-dd'
                value="<?php if ($model->option == 'date') echo htmlspecialchars($model->from) ?>"
                onFocus='document.getElementById("radioDate").checked = true;'
                />
                <label>
                    <input type='radio' name='ShowForm[option]' value='pattern' id='radioPattern'
                    <?php if ($model->option == 'pattern') echo "checked='checked'" ?> />
                    <?php echo $this->i18n->get('Contains'); ?>
                </label>
                &nbsp;
                <input type='text' name='ShowForm[pattern]' size='16' 
                value="<?php if ($model->option == 'pattern') echo htmlspecialchars($model->pattern) ?>"
                onFocus='document.getElementById("radioPattern").checked = true;'
                />
                &nbsp;
                <input type='submit' value='<?php echo $this->i18n->get('Show'); ?>' />
            </fieldset>
        </form>
<?php if (isset($listing)) echo $listing; ?>
        <p><a href='#top'>[<?php echo $this->i18n->get('top'); ?>]</a></p>
    </div>
</div>