<?php
/**
 * SubscribersPlugin for phplist.
 *
 * This file is a part of SubscribersPlugin.
 *
 * @author    Duncan Cameron
 * @copyright 2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * Class to convert checkbox group attribute values from id to name.
 *
 * It wraps a DBResultIterator overriding the current() method to convert the
 * user attribute value to a list of names.
 */

namespace phpList\plugin\SubscribersPlugin;

class CbgConverter extends \IteratorIterator implements \Countable
{
    /** @var array checkbox group attributes */
    private $cbgAttributes;
    /** @var phpList\plugin\SubscribersPlugin\DAO\User DAO */
    private $dao;

    public function __construct(\Iterator $iterator, array $cbgAttributes, DAO\User $dao)
    {
        parent::__construct($iterator);
        $this->cbgAttributes = $cbgAttributes;
        $this->dao = $dao;
    }

    /**
     * Override parent method to convert each cbg result column from ids to names
     * e.g. 1,3,4 => red, blue, yellow.
     *
     * @return array modified current iterator item
     */
    public function current()
    {
        $current = parent::current();

        foreach ($this->cbgAttributes as $attrId => $attr) {
            $column = 'attr' . $attrId;

            if (!isset($current[$column])) {
                continue;
            }

            if (!preg_match('/^\d+(,\d+)*$/', $current[$column])) {
                continue;
            }
            $names = $this->dao->cbgNames($attr, $current[$column]);
            $current[$column] = implode(', ', $names);
        }

        return $current;
    }

    /**
     * Implement the Countable interface allowing the count() function to be used.
     *
     * @return int count() of the inner iterator
     */
    public function count(): int
    {
        return $this->getInnerIterator()->count();
    }
}
