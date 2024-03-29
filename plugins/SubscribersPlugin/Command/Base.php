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

namespace phpList\plugin\SubscribersPlugin\Command;

use CHtml;

/**
 *  This is the parent class for the command classes.
 */
abstract class Base
{
    protected $commandId;
    protected $dao;
    protected $i18n;
    protected $additionalFields;

    protected function listsDropDown($selected, $fieldId, $disabled)
    {
        return CHtml::dropDownList(
            sprintf('additional[command][%d][%s]', $this->commandId, $fieldId),
            $selected,
            CHtml::listData($this->dao->listsForOwner(null), 'id', 'name'),
            ['disabled' => $disabled]
        );
    }

    /**
     * Overridden when a class has processing to be run on construction.
     */
    protected function initialise()
    {
    }

    public function __construct($commandId, $additionalFields, $dao, $i18n)
    {
        $this->commandId = $commandId;
        $this->additionalFields = $additionalFields;
        $this->dao = $dao;
        $this->i18n = $i18n;
        $this->initialise();
    }

    /**
     * Allow a command to validate its parameters.
     *
     * @throws Exception if the parameters are not valid
     */
    public function validate()
    {
    }

    /**
     * Decide whether to accept a subscriber for processing by the command.
     *
     * @param array $user user details
     *
     * @return bool whether to accept
     */
    public function accept(array $user)
    {
        return true;
    }

    /**
     * Process a subscriber.
     *
     * @param array $user user details
     *
     * @return bool whether the subscriber was processed successfully
     */
    abstract public function process(array $user);

    /**
     * Create the result message specific to this command.
     *
     * @param int $count the number of subscribers processed successfully
     *
     * @return string the result message
     */
    abstract public function result($count);

    /**
     * Generate additional html to be added to the command page.
     *
     * @param bool $disabled whether html input fields should be disabled
     *
     * @return string the html
     */
    public function additionalCommandHtml($disabled)
    {
        return '';
    }

    /**
     * Generate additional html to be added to the display users page.
     *
     * @return string the html
     */
    public function additionalHtml()
    {
        return '';
    }
}
