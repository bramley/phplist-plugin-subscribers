<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\I18n;
use phpList\plugin\Common\IPopulator;
use phpList\plugin\Common\PageLink;
use phpList\plugin\Common\PageURL;
use phpList\plugin\Common\WebblerListing;

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
class InvalidPopulator implements IPopulator
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
    public function populate(WebblerListing $w, $start, $limit)
    {
        $w->setTitle($this->i18n->get('Subscribers with an invalid email address'));
        $w->setElementHeading('#');
        $end = min($start + $limit, count($this->invalid));

        for ($i = $start; $i < $end; ++$i) {
            $key = $i + 1;
            $w->addElement($key);
            $w->addColumnHtml(
                $key,
                $this->i18n->get('Subscriber'),
                new PageLink(
                    new PageURL('user', array('id' => $this->invalid[$i]['id'])),
                    $this->invalid[$i]['email'],
                    array('target' => '_blank')
                )
            );
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
}
