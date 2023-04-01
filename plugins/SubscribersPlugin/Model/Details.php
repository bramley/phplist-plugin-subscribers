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
    private $referencedAttributes;
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
        'orderBy' => null,
        'listID' => null,
    );
    protected $persist = array(
        'confirmed' => '',
        'blacklisted' => '',
        'selectedAttrs' => '',
        'searchTerm' => '',
        'searchBy' => '',
        'orderBy' => '',
        'listID' => '',
    );
    /*
     *    Public variables
     */
    public $attributes;
    public $lists;

    public function __construct(User $dao, array $attributes, DAOList $listDAO)
    {
        parent::__construct('SubscribersPl_D');
        $this->access = accessLevel('users');
        $this->loginId = ($this->access == 'owner') ? $_SESSION['logindetails']['id'] : '';

        $this->dao = $dao;
        $this->attributes = $attributes;
        $this->listDAO = $listDAO;
        $this->lists = $this->listDAO->listsForOwner($this->loginId);
        // remove non-existent selected attributes and ensure that searchBy and orderBy are included
        $this->selectedAttrs = array_intersect(
            array_unique(array_merge($this->selectedAttrs, [$this->searchBy, $this->orderBy])),
            array_keys($this->attributes)
        );
        // process only those attributes that are actually used
        $this->referencedAttributes = array_intersect_key(
            $this->attributes,
            array_flip($this->selectedAttrs)
        );
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
        $users = $this->dao->users($this->listID, $this->loginId, $this->referencedAttributes,
            $this->searchTerm, $this->searchBy, $this->orderBy, $this->confirmed, $this->blacklisted, $start, $limit
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
            $this->listID, $this->loginId, $this->referencedAttributes, $this->searchTerm, $this->searchBy,
            $this->confirmed, $this->blacklisted
        );
    }
}
