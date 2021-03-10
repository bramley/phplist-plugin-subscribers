<?php
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

namespace phpList\plugin\SubscribersPlugin;

use Iterator;
use LimitIterator;
use phpList\plugin\Common\I18n;
use phpList\plugin\Common\IExportable;
use phpList\plugin\Common\ImageTag;
use phpList\plugin\Common\IPopulator;
use phpList\plugin\Common\PageURL;

/**
 * This class populates a listing with subscriber email addresses.
 */
class SubscriberPopulator implements IPopulator, IExportable
{
    private $i18n;
    private $subscriberIterator;
    private $title;
    private $showConfirmedColumn;
    private $columnCallback;
    private $valuesCallback;

    /**
     * Constructor.
     *
     * @param I18n     $i18n                language selector
     * @param Iterator $subscriberIterator  provides the subscribers to be listed
     * @param string   $title               title for the listing or file name for exporting
     * @param bool     $showConfirmedColumn whether to display the confirmed and blacklisted columns
     * @param callable $columnCallback      function to provide names of additional columns
     * @param callable $valuesCallback      function to provide values of additional columns
     */
    public function __construct(I18n $i18n, Iterator $subscriberIterator, $title, $showConfirmedColumn, $columnCallback = null, $valuesCallback = null)
    {
        $this->i18n = $i18n;
        $this->subscriberIterator = $subscriberIterator;
        $this->title = $title;
        $this->showConfirmedColumn = $showConfirmedColumn;
        $this->columnCallback = $columnCallback;
        $this->valuesCallback = $valuesCallback;
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
        $w->setTitle($this->title);
        $w->setElementHeading($this->i18n->get('Subscriber'));
        $limitIterator = new LimitIterator($this->subscriberIterator, $start, $limit);

        foreach ($limitIterator as $row) {
            $key = $row['email'];
            $w->addElement($key, new PageURL('user', array('id' => $row['id'])));

            if ($this->columnCallback) {
                $valuesCallback = $this->valuesCallback;
                $values = $valuesCallback($row);
                $columnCallback = $this->columnCallback;

                foreach ($columnCallback() as $i => $name) {
                    $w->addColumn($key, $name, $values[$i]);
                }
            }

            if ($this->showConfirmedColumn) {
                $value = $row['confirmed']
                    ? ''
                    : new ImageTag('no.png', $this->i18n->get('not confirmed'));
                $w->addColumnHtml($key, $this->i18n->get('confirmed_heading'), $value);
                $value = $row['blacklisted']
                    ? new ImageTag('user.png', $this->i18n->get('User is blacklisted'))
                    : '';
                $w->addColumnHtml($key, $this->i18n->get('blacklisted_heading'), $value);
            }
        }
    }

    /**
     * The number of available items.
     *
     * @return int the number of subscribers
     */
    public function total()
    {
        return count($this->subscriberIterator);
    }

    /*
     * Implementation of IExportable
     */
    public function exportFileName()
    {
        return $this->title;
    }

    public function exportRows()
    {
        return $this->subscriberIterator;
    }

    public function exportFieldNames()
    {
        $fields = [$this->i18n->get('email')];

        if ($this->columnCallback) {
            $columnCallback = $this->columnCallback;
            $fields = array_merge($fields, $columnCallback());
        }
        if ($this->showConfirmedColumn) {
            $fields[] = $this->i18n->get('confirmed');
            $fields[] = $this->i18n->get('blacklisted');
        }

        return $fields;
    }

    public function exportValues(array $row)
    {
        $values = [$row['email']];

        if ($this->valuesCallback) {
            $valuesCallback = $this->valuesCallback;
            $values = array_merge($values, $valuesCallback($row));
        }

        if ($this->showConfirmedColumn) {
            $values[] = $row['confirmed'];
            $values[] = $row['blacklisted'];
        }

        return $values;
    }
}
