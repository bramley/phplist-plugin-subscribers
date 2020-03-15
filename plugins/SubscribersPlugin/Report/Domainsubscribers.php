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
 * @copyright 2011-2019 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\Report;

/**
 * {@inheritdoc}
 */
class Domainsubscribers extends AbstractReport
{
    public function __construct()
    {
        parent::__construct();
        $this->domain = $_GET['domain'];
    }

    public function iterator($dao)
    {
        return $dao->domainSubscribers($this->domain);
    }

    public function title()
    {
        return $this->i18n->get('Subscribers on domain %s', $this->domain);
    }

    public function noSubscribersWarning()
    {
        return $this->i18n->get('Domain %s does not have any subscribers', $this->domain);
    }
}
