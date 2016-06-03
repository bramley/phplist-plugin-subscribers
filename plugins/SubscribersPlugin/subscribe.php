<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\DB;
use phpList\plugin\Common\DAO\Lists;

function displayResultPage($result, $uid = '')
{
    global $pagedata, $PoweredBy;

    $r = htmlspecialchars($result);
    $preferencesUrl = htmlspecialchars(getConfig('preferencesurl') . "&uid=$uid");

    echo <<<END
<title>List subscription</title>
{$pagedata['header']}
<div class='note'>$r</div>
<h3>To change your details and to choose which lists to be subscribed to, visit your <a href="$preferencesUrl">preferences</a> page.</h3>
$PoweredBy
{$pagedata['footer']}
END;
}

function listSubscribe()
{
    if (!(isset($_GET['uid']))) {
        displayResultPage('A uid must be specified');

        return;
    }
    $uid = $_GET['uid'];

    if (!(isset($_GET['list']) && ctype_digit($_GET['list']))) {
        displayResultPage('A numeric list id must be specified', $uid);

        return;
    }
    $listId = $_GET['list'];

    $dao = new DAO\Unsubscribe(new DB());

    if (!($user = $dao->userByUniqid($uid))) {
        displayResultPage("Unknown uid $uid", '');

        return;
    }
    $userId = $user['id'];
    $email = $user['email'];

    $listDao = new Lists(new DB());

    if (!($list = $listDao->listById($listId))) {
        displayResultPage("Unknown list id $listId", $uid);

        return;
    }
    $listName = $list['name'];

    $rowCount = $dao->addSubscriberToList($userId, $listId);

    if ($rowCount > 0) {
        $body = "$email has been added to list '$listName'";
        addUserHistory(
            $email,
            'Added to list',
            "Added to list '$listName'"
        );
    } else {
        $body = "$email already belongs to list '$listName'";
    }

    displayResultPage($body, $uid);
}

listSubscribe();
