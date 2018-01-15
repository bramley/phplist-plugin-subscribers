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

namespace phpList\plugin\SubscribersPlugin\Controller;

use phpList\plugin\Common\Controller;
use phpList\plugin\Common\PageLink;
use phpList\plugin\Common\PageURL;

/**
 * This class is the controller for the plugin providing the action methods.
 */
class Reports extends Controller
{
    const TEMPLATE = '/../view/reports.tpl.php';

    /**
     * Displays the report menu.
     */
    protected function actionDefault()
    {
        $items = [
            ['params' => ['report' => 'invalid'], 'caption' => $this->i18n->get('Subscribers with an invalid email address')],
            ['params' => ['report' => 'inactive'], 'caption' => $this->i18n->get('Inactive subscribers')],
            ['params' => ['report' => 'nolist'], 'caption' => $this->i18n->get('Subscribers who do not belong to a list')],
        ];
        $links = [];

        foreach ($items as $item) {
            $links[] = [
                'caption' => $item['caption'],
                'button' => new PageLink(
                    PageURL::createFromGet($item['params']),
                    htmlspecialchars($this->i18n->get('Run')),
                    ['class' => 'button']
                ),
            ];
        }
        $vars = [
            'links' => $links,
            'heading' => $this->i18n->get('Available reports'),
        ];
        echo $this->render(__DIR__ . self::TEMPLATE, $vars);
    }
}
