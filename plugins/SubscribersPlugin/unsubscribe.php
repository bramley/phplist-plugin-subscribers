<?php

namespace phpList\plugin\SubscribersPlugin;

require 'ListSubscription.php';

$subscribe = new ListSubscription();
$confirm = $_GET['confirm'] ?? 0;

if ($confirm) {
    $subscribe->messageUnsubscribe();
} else {
    $subscribe->noAction();
}
