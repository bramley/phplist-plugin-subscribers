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
<div>
<?php if (isset($warning)) : ?>
    <div class="note alert alert-warning"><?= $warning; ?></div>
<?php endif; ?>
<?= $toolbar; ?>
<?php if (isset($refresh)) : ?>
    <div><?= $refresh; ?></div>
<?php endif; ?>
    <div style='padding-top: 10px; text-align: right;'>
<?php if (isset($command_link)) : ?>
    <?= $command_link; ?>
<?php endif; ?>
    </div>
    <div style='padding-top: 10px;' >
<?= $listing ?>
    </div>
<?php if (isset($command_link)) : ?>
    <div><?= $command_link; ?></div>
<?php endif; ?>
</div>
