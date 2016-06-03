<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\DB;

function displayResultPage($result, $uid = '')
{
    global $pagedata, $PoweredBy, $strUnsubscribeTitle;

    $title = htmlspecialchars($strUnsubscribeTitle);
    $r = htmlspecialchars($result);
    $preferencesUrl = htmlspecialchars(getConfig('preferencesurl') . "&uid=$uid");

    echo <<<END
<title>$title</title>
{$pagedata['header']}
<div class='note'>$r</div>
<h3>To change your details and to choose which lists to be subscribed to, visit your <a href="$preferencesUrl">preferences</a> page.</h3>
$PoweredBy
{$pagedata['footer']}
END;
}

function wrapInQuotes($array)
{
    return array_map(
        function ($value) {
            return '"' . $value . '"';
        },
        $array
    );
}

function listUnsubscribe()
{
    global $strUnsubscribeTitle;

    if (!(isset($_GET['m']) && ctype_digit($_GET['m']))) {
        displayResultPage(s('A numeric message id must be specified'));
        exit;
    }

    if (!(isset($_GET['uid']))) {
        displayResultPage(s('A uid must be specified'));
        exit;
    }

    $mid = $_GET['m'];
    $uid = $_GET['uid'];
    $dao = new DAO\Unsubscribe(new DB());

    $row = $dao->userByUniqid($uid);

    if (!$row) {
        displayResultPage(s('Unknown uid %s', $uid));
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

    if (count($removed) > 0) {
        $joined = implode(', ', wrapInQuotes($removed));
        $result = "$email has been removed from $joined";
    } else {
        $result = "$email has not been removed from any lists";
    }
    echo displayResultPage($result, $uid);
}

listUnsubscribe();
