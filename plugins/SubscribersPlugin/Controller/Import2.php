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

namespace phpList\plugin\SubscribersPlugin\Controller;

use phpList\plugin\Common\Context;
use phpList\plugin\Common\Controller;
use phpList\plugin\Common\DAO\Attribute as AttributeDAO;

class Import2 extends Controller
{
    private $dao;
    private $context;

    /**
     * Use the core phplist import2 page to import a file.
     */
    private function importFile($file, $listId)
    {
        global $tmpdir, $tables, $table_prefix, $admin_auth, $systemroot, $envelope;

        if (!is_readable($file) || ($handle = fopen($file, 'r')) === false) {
            echo "unable to open $file\n";
            exit(2);
        }
        $columnNames = fgetcsv($handle, 0, ',');
        $attributes = iterator_to_array($this->dao->attributes());
        $attributesByName = array_column($attributes, 'id', 'name');
        $emailPosition = null;
        $_SESSION['import_attribute'] = array();

        foreach ($columnNames as $i => $name) {
            if ($name == 'email') {
                $emailPosition = $i;
                continue;
            }

            if (isset($attributesByName[$name])) {
                $_SESSION['import_attribute'][$name] = array(
                    'index' => $i,
                    'record' => $attributesByName[$name],
                    'column' => $name,
                );
                continue;
            }
        }

        if ($emailPosition === null) {
            echo "no email column\n";
            exit(3);
        }
        $tempName = $tmpdir . '/' . uniqid('phplist_import', true);

        if (!copy($file, $tempName)) {
            echo "unable to create temporary file\n";
            exit;
        }

        $_SESSION['import_file'] = $tempName;
        $_SESSION['import_record_delimiter'] = "\n";
        $_SESSION['import_field_delimiter'] = ',';
        $_SESSION['test_import'] = false;
        $_SESSION['lists'] = array($listId);
        $_SESSION['show_warnings'] = true;
        $_SESSION['overwrite'] = true;
        $_SESSION['notify'] = 'no';         // actually confirmation required
        $_SESSION['groups'] = null;
        $_SESSION['systemindex'] = array(
            'email' => $emailPosition,
        );
        $_SESSION['logindetails']['id'] = 1;
        $unused_systemattr = array();

        include $systemroot . '/actions/import2.php';
    }

    protected function actionDefault()
    {
        global $commandline;

        $this->context->start();

        if (!$commandline) {
            $this->context->output("This page can be run only from the command line\n");
            exit;
        }
        $options = getopt('f:l:p:c:m:');

        if (!(isset($options['f']) && isset($options['l']))) {
            $this->context->output("the file to import and list id are required\n");
            exit(1);
        }
        $this->importFile($options['f'], $options['l']);
        $this->context->finish();

        exit;
    }

    public function __construct(AttributeDAO $dao, Context $context)
    {
        parent::__construct();
        $this->dao = $dao;
        $this->context = $context;
    }
}
