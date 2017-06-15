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

/**
 * This class holds the properties entered in the search form.
 */

namespace phpList\plugin\SubscribersPlugin\Model;

use phpList\plugin\Common\DAO\Attribute as DAOAttribute;
use phpList\plugin\Common\DAO\Lists as DAOList;
use phpList\plugin\Common\Model;
use phpList\plugin\SubscribersPlugin\CbgConverter;
use phpList\plugin\SubscribersPlugin\DAO\User;

class Details extends Model
{
    /*
     *    private variables
     */
    private $dao;
    private $attributeDAO;
    private $listDAO;
    private $access;
    private $loginId;
    /*
     *    inherited protected variables
     */
    protected $properties = array(
        'type' => 'details',
        'confirmed' => 0,
        'blacklisted' => 0,
        'selectedAttrs' => array(),
        'searchTerm' => null,
        'searchBy' => null,
        'listID' => null,
    );
    protected $persist = array(
        'confirmed' => '',
        'blacklisted' => '',
        'selectedAttrs' => '',
        'searchTerm' => '',
        'searchBy' => '',
        'listID' => '',
    );
    /*
     *    Public variables
     */
    public $attributes;
    public $lists;

    /*
     *    Private methods
     */
    private function verifySelectedAttributes()
    {
        /*
         * remove selected attributes that no longer exist and re-index
         */
        $this->properties['selectedAttrs'] = array_values(
            array_filter(
                $this->properties['selectedAttrs'],
                function ($v) {
                    return isset($this->attributes[$v]);
                }
            )
        );
    }

    /*
     *    Public methods
     */
    public function __construct(User $dao, DAOAttribute $attributeDAO, DAOList $listDAO)
    {
        parent::__construct('SubscribersPl_D');
        $this->access = accessLevel('users');
        $this->loginId = ($this->access == 'owner') ? $_SESSION['logindetails']['id'] : '';

        $this->dao = $dao;
        $this->attributeDAO = $attributeDAO;
        $this->listDAO = $listDAO;
        $this->attributes = $this->attributeDAO->attributesById();
        $this->lists = $this->listDAO->listsForOwner($this->loginId);

        $this->verifySelectedAttributes();
    }

    /**
     * Runs the query for subscribers using form selection fields held in the model.
     * The results for checkbox group attributes are post-processed by converting the set of attribute value ids to the
     * attribute value names.
     *
     * @param int|null $start
     * @param int|null $limit
     *
     * @return iterator
     */
    public function users($start = null, $limit = null)
    {
        $users = $this->dao->users($this->listID, $this->loginId, $this->attributes,
            $this->searchTerm, $this->searchBy, $this->confirmed, $this->blacklisted, $start, $limit
        );

        $cbgAttributes = array_filter(
            $this->attributes,
            function ($attr) {
                return $attr['type'] == 'checkboxgroup';
            }
        );

        return count($cbgAttributes) > 0
            ? new CbgConverter($users, $cbgAttributes, $this->dao)
            : $users;
    }

    /**
     * Runs the query for the total number of subscribers using form fields held in the model.
     *
     * @return int the number of subscribers matching the selection conditions
     */
    public function totalUsers()
    {
        return $this->dao->totalUsers(
            $this->listID, $this->loginId, $this->attributes, $this->searchTerm, $this->searchBy,
            $this->confirmed, $this->blacklisted
        );
    }
}
