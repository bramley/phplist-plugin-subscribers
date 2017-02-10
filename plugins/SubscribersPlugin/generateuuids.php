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
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */
error_reporting(-1);

if ($commandline) {
    $output = function ($line) {
        cl_output($line);
    };
    ob_end_clean();
    echo ClineSignature();
    $options = getopt('p:c:m:s:');
    $batchSize = isset($options['s']) ? $options['s'] : 20000;
} else {
    $output = function ($line) {
        echo "$line<br/>\n";
        @ob_flush();
        flush();
    };
    ob_end_flush();
    $batchSize = isset($_GET['size']) ? $_GET['size'] : 5000;
}
ob_start();

if (!version_compare(getConfig('version'), '3.3') > 0) {
    $output('This page applies to phplist 3.3.0 and later');

    return;
}
$req = Sql_Query(sprintf('select id from %s where uuid is NULL or uuid = ""', $tables['user']));
$num = Sql_Affected_Rows();

if ($num) {
    $output(s('There are %d subscribers without a UUID', $num));

    for ($i = 0; $i < $batchSize; ++$i) {
        $row = Sql_Fetch_Row($req);

        if (!$row) {
            break;
        }
        Sql_query(sprintf('update %s set uuid = "%s" where id = %d', $tables['user'], (string) \uuid::generate(4), $row[0]));
    }
    $output("$i subscribers processed");
} else {
    $output('All subscribers have UUIDs');
}
