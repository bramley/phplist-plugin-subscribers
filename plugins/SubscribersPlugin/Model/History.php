<?php
/**
 * SubscribersPlugin for phplist
 * 
 * This file is a part of SubscribersPlugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2011 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class holds the properties entered in the search form
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */
class SubscribersPlugin_Model_History extends CommonPlugin_Model
{
    /*
     *    private variables
     */
    private $dao;
    private $searchBy;
    private $param;

    /*
     *    Inherited protected variables
     */
    protected $properties = array(
        'type' => null,
        'option' => 'all',
        'pattern' => null,
        'from' => null
    );
    protected $persist = array(
        'option' => '',
        'pattern' => '',
        'from' => ''
    );
    /*
     *    Private methods
     */
    private function init()
    {
        $this->searchBy = $this->option;
        switch ($this->option) {
            case 'pattern':
                $this->param = $this->pattern;
                break;
            case 'date':
                date_default_timezone_set('UTC');

                if ($this->from) {
                    $t = strtotime($this->from);

                    if ($t === false) {
                        $t = time() - 86400 * 28;
                    }
                } else {
                    $t = time() - (86400 * 14);
                }
                $this->from = date('Y-m-d', $t);
                $this->param = $this->from;
                break;
            case 'all':
                $this->searchBy = null;
                $this->param = null;
                break;
            default:
                throw new Exception("Unrecognised option: $this->option");
        }
    }
    /*
     *    Public methods
     */
    public function __construct($db)
    {
        $this->dao = new SubscribersPlugin_DAO_Event($db);
        parent::__construct('SubscribersPl_H');
        $this->init();
    }

    public function setProperties(array $new)
    {
        parent::setProperties($new);
        $this->init();
    }

    public function listEvents($start = null, $limit = null)
     {
        return $this->dao->listEvents($this->searchBy, $this->param, $start, $limit);
    }

    public function totalEvents()
     {
        return $this->dao->totalEvents($this->searchBy, $this->param);
    }

}
