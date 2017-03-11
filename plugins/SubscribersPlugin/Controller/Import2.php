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

class Import2 extends Controller
{
    private $attributes;
    private $context;

    /**
     * Use the core phplist import2 page to import a file.
     */
    private function importFile($file, $listId)
    {
        global $tmpdir, $tables, $table_prefix, $admin_auth, $systemroot, $envelope, $DBstruct;

        if (!is_readable($file) || ($handle = fopen($file, 'r')) === false) {
            $this->context->output("unable to open $file\n");

            return;
        }
        $columnNames = fgetcsv($handle, 0, ',');
        $attributesByName = array_column($this->attributes, 'id', 'name');
        $emailPosition = null;
        $_SESSION['import_attribute'] = array();
        $_SESSION['systemindex'] = array();

        foreach ($columnNames as $i => $name) {
            if ($name == 'email') {
                $_SESSION['systemindex']['email'] = $i;
                $emailPosition = $i;
                continue;
            }

            if ($name == 'foreignkey') {
                $_SESSION['systemindex']['foreignkey'] = $i;
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
            $this->context->output("no email column\n");

            return;
        }
        $tempName = $tmpdir . '/' . uniqid('phplist_import', true);

        if (!copy($file, $tempName)) {
            $this->context->output("unable to create temporary file\n");

            return;
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
        $_SESSION['logindetails']['id'] = 1;
        $unused_systemattr = array();

        require $systemroot . '/actions/import2.php';
    }

    protected function actionDefault()
    {
        global $commandline, $inRemoteCall, $installation_name;

        $this->context->start();

        if ($commandline) {
            $options = getopt('f:l:p:c:m:');

            if (isset($options['f']) && isset($options['l'])) {
                $this->importFile($options['f'], $options['l']);
            } else {
                $this->context->output("the file to import and list id are required\n");
            }
        } elseif ($inRemoteCall) {
            if (isset($_FILES['import_file']) && isset($_POST['list_id'])) {
                $file = $_FILES['import_file'];
                // Add token because it is checked by the import page
                $_GET['tk'] = $_SESSION[$installation_name . '_csrf_token'];
                $this->importFile($file['tmp_name'], $_POST['list_id']);
            } else {
                $this->context->output("the file to import and list id are required\n");
            }
        } else {
            $this->context->output("This page can be run only from the command line or a as a remote page\n");
        }
        $this->context->finish();

        exit;
    }

    public function __construct(array $attributes, Context $context)
    {
        parent::__construct();
        $this->attributes = $attributes;
        $this->context = $context;
    }
}
