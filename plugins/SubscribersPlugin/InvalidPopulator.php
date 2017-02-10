<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\I18n;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\IPopulator;
use phpList\plugin\Common\PageURL;

/**
 * SubscribersPlugin for phplist.
 *
 * This file is a part of SubscribersPlugin.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class populates a listing with invalid email addresses.
 */
class InvalidPopulator implements IPopulator, IExportable
{
    private $i18n;
    private $invalid;

    /**
     * Constructor.
     *
     * @param I18n  $i18n    language selector
     * @param array $invalid invalid subscribers - id and email address
     */
    public function __construct(I18n $i18n, array $invalid)
    {
        $this->i18n = $i18n;
        $this->invalid = $invalid;
    }

    /**
     * Populate the listing.
     *
     * @param WebblerListing $w     listing to be populated
     * @param int            $start index of the first item
     * @param int            $limit maximum number of items to display
     */
    public function populate(\WebblerListing $w, $start, $limit)
    {
        $w->setTitle($this->i18n->get('Subscribers with an invalid email address'));
        $w->setElementHeading($this->i18n->get('Subscriber'));
        $end = min($start + $limit, count($this->invalid));

        for ($i = $start; $i < $end; ++$i) {
            $key = $this->invalid[$i]['email'];
            $w->addElement($key, new PageURL('user', array('id' => $this->invalid[$i]['id'])));
        }
    }

    /**
     * The number of available items.
     *
     * @return int the number of invalid emails
     */
    public function total()
    {
        return count($this->invalid);
    }

    /*
     * Implementation of IExportable
     */
    public function exportFileName()
    {
        return 'invalid_email';
    }

    public function exportRows()
    {
        return $this->invalid;
    }

    public function exportFieldNames()
    {
        return [$this->i18n->get('email')];
    }

    public function exportValues(array $row)
    {
        return [$row['email']];
    }
}
