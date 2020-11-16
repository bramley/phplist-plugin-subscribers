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
 * @copyright 2011-2020 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\Model;

use phpList\plugin\Common\Model;

class Command extends Model
{
    /*
     *  Inherited protected variables
     */
    protected $properties = array(
        'commandid' => 0,
        'file' => null,
        'emails' => '',
        'pattern' => '',
        'acceptedEmails' => null,
        'additional' => [],
    );
    protected $persist = array(
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function setProperties(array $new)
    {
        parent::setproperties($new);

        if (isset($_FILES['file'])) {
            $this->file = $_FILES['file'];
        }
    }
}
