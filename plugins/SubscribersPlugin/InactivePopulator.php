<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\I18n;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\IPopulator;
use phpList\plugin\Common\PageURL;
use phpList\plugin\SubscribersPlugin\DAO\Command as DAO;

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
 * This class populates a listing with inactive email addresses.
 */
class InactivePopulator implements IPopulator, IExportable
{
    private $dao;
    private $i18n;
    private $interval;

    /**
     * Constructor.
     *
     * @param DAO  $dao      DAO
     * @param I18n $i18n     language selector
     * @param int  $interval inactive interval
     */
    public function __construct(DAO $dao, I18n $i18n, $interval)
    {
        $this->dao = $dao;
        $this->i18n = $i18n;
        $this->interval = $interval;
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
        $w->setTitle($this->i18n->get('Inactive subscribers'));
        $w->setElementHeading($this->i18n->get('Subscriber'));
        $subscribers = $this->dao->inactiveSubscribersByInterval($this->interval, $start, $limit);

        foreach ($subscribers as $subscriber) {
            $key = $subscriber['email'];
            $w->addElement($key, new PageURL('userhistory', ['id' => $subscriber['id']]));
            $w->addColumn($key, $this->i18n->get('Lists'), $subscriber['listname']);
            $w->addColumn(
                $key,
                $this->i18n->get('Number of campaigns'),
                sprintf('%d | %d', $subscriber['recent_campaigns'], $subscriber['total_campaigns'])
            );
            $w->addColumnHtml($key, $this->i18n->get('Last view'), formatDate($subscriber['lastview'], true));
        }
    }

    /**
     * The number of available items.
     *
     * @return int the number of invalid emails
     */
    public function total()
    {
        return $this->dao->countInactiveSubscribersByInterval($this->interval);
    }

    /*
     * Implementation of IExportable
     */
    public function exportFileName()
    {
        return 'inactive_subscribers';
    }

    public function exportRows()
    {
        return $this->dao->inactiveSubscribersByInterval($this->interval);
    }

    public function exportFieldNames()
    {
        return [
            $this->i18n->get('id'),
            $this->i18n->get('Subscriber'),
            $this->i18n->get('Lists'),
            $this->i18n->get('Recent campaigns'),
            $this->i18n->get('Total campaigns'),
            $this->i18n->get('Last view'),
        ];
    }

    public function exportValues(array $row)
    {
        return [
            $row['id'],
            $row['email'],
            $row['listname'],
            $row['recent_campaigns'],
            $row['total_campaigns'],
            $row['lastview'],
        ];
    }
}
