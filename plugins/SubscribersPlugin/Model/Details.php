<?php
/**
 * SubscribersPlugin for phplist
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
 * @category  phplist
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2011-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class holds the properties entered in the search form
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */
class SubscribersPlugin_Model_Details extends CommonPlugin_Model
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
        'listID' => null
    );
    protected $persist = array(
        'confirmed' => '',
        'blacklisted' => '',
        'selectedAttrs' => '',
        'searchTerm' => '',
        'searchBy' => '',
        'listID' => ''
    );
    /*
     *    Public variables
     */
    public $attributes;
    public $lists;
    /*
     *    Private methods
     */
    private function filter($v)
    {
        return isset($this->attributes[$v]);
    }

    private function verifySelectedAttributes()
    {
        /*         
         * remove selected attributes that no longer exist and re-index
         */
        $this->properties['selectedAttrs'] 
            = array_values(array_filter($this->properties['selectedAttrs'], array($this, 'filter')));
    }
    /*
     *    Public methods
     */
    public function __construct($db)
    {
        parent::__construct('SubscribersPlugin_Details');
        $this->access = accessLevel('users');
        $this->loginId = ($this->access == 'owner') ? $_SESSION['logindetails']['id'] : '';

        $this->dao = new SubscribersPlugin_DAO_User($db);
        $this->attributeDAO = new CommonPlugin_DAO_Attribute($db);
        $this->listDAO = new CommonPlugin_DAO_List($db);
        $this->attributes = $this->attributeDAO->attributesById();
        $this->lists = $this->listDAO->listsForOwner($this->loginId);

        $this->verifySelectedAttributes();
    }

    public function users($start = null, $limit = null)
    {
        return $this->dao->users($this->listID, $this->loginId, $this->attributes,
            $this->searchTerm, $this->searchBy, $this->confirmed, $this->blacklisted, $start, $limit
        );
    }

    public function totalUsers()
    {
        return $this->dao->totalUsers(
            $this->listID, $this->loginId, $this->attributes, $this->searchTerm, $this->searchBy,
            $this->confirmed, $this->blacklisted
        );
    }
}
