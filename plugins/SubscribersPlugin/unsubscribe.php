<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\DB;

if (!(isset($_GET['m']) && ctype_digit($_GET['m']))) {
    echo s('A numeric message id must be specified');
    exit;
}

if (!(isset($_GET['uid']))) {
    echo s('A uid must be specified');
    exit;
}

$mid = $_GET['m'];
$uid = sql_escape($_GET['uid']);
$dao = new DAO\Unsubscribe(new DB());

$row = $dao->userByUniqid($uid);

if (!$row) {
    echo "Unknown uid $uid";
    exit;
}
$userId = $row['id'];
$email = $row['email'];

$removed = array();

foreach ($dao->listsForSubscriberMessage($userId, $mid) as $row) {
    $count = $dao->removeSubscriberFromList($userId, $row['listid']);

    if ($count > 0) {
        $removed[] = $row['name'];
        addUserHistory(
            $email,
            'Removed from list',
            "Removed from list {$row['listid']} '{$row['name']}' through campaign $mid"
        );
    }
}

$page = '<title>' . $strUnsubscribeTitle . '</title>' . "\n";
$page .= $pagedata['header'];

if (count($removed) > 0) {
    $joined = implode('", "', $removed);
    $joined = '"' . $joined . '"';
    $page .= '<h3>' . $email . ' has been removed from ' . $joined . '</h3>';
} else {
    $page .= '<h3>' . $email . ' has not been removed from any lists' . '</h3>';
}
$page .= $PoweredBy . '</p>';
$page .= $pagedata['footer'];
echo $page;
