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
 * @copyright 2011-2022 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

namespace phpList\plugin\SubscribersPlugin\Command;

use CHtml;

/**
 *  This class implements the command to empty an attribute value for a subscriber.
 */
class EmptyAttribute extends Base
{
    private $attributeId;
    private $attributes;

    public function initialise()
    {
        $this->attributes = $this->dao->attributesById();

        if (isset($this->additionalFields['command'][$this->commandId]['attributeId'])) {
            $this->attributeId = $this->additionalFields['command'][$this->commandId]['attributeId'];
        } else {
            $this->attributeId = 0;
        }
    }

    public function accept(array $user)
    {
        return (bool) $this->dao->subscriberHasAttributeValue($user['id'], $this->attributeId);
    }

    public function process(array $user)
    {
        $rows = $this->dao->updateUserAttribute($user['email'], $this->attributeId, '');

        return $rows > 0;
    }

    public function result($count)
    {
        return $this->i18n->get('result_empty_attribute', $this->attributes[$this->attributeId]['name'], $count);
    }

    public function additionalCommandHtml($disabled)
    {
        return CHtml::dropDownList(
            sprintf('additional[command][%d][attributeId]', $this->commandId),
            $this->attributeId,
            array_column($this->attributes, 'name', 'id'),
            ['disabled' => $disabled]
        );
    }
}
