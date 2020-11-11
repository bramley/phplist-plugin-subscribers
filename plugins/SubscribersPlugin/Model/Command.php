<?php

namespace phpList\plugin\SubscribersPlugin\Model;

use phpList\plugin\Common\Model;

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
class Command extends Model
{
    /*
     *  Inherited protected variables
     */
    protected $properties = array(
        'commandid' => null,
        'file' => null,
        'emails' => '',
        'pattern' => null,
        'acceptedEmails' => null,
        'additional' => [],
    );
    protected $persist = array(
    );

    /*
     *  Public methods
     */
    public function __construct($defaultCommand)
    {
        $this->properties['commandid'] = $defaultCommand;
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
