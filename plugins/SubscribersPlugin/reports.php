<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\I18n;
use phpList\plugin\Common\PageLink;
use phpList\plugin\Common\PageURL;

error_reporting(-1);

function menu()
{
    $i18n = I18n::instance();
    $items = [
        ['page' => 'invalid', 'params' => [], 'caption' => $i18n->get('Subscribers with an invalid email address')],
        ['page' => 'inactive', 'params' => [], 'caption' => $i18n->get('Inactive subscribers')],
    ];
    $view = __DIR__ . '/' . 'view/reports.tpl.php';
    $links = [];

    foreach ($items as $item) {
        $links[] = [
            'caption' => $item['caption'],
            'button' => new PageLink(
                new PageURL($item['page'], ['pi' => $_GET['pi']] + $item['params']),
                htmlspecialchars($i18n->get('Run')),
                ['class' => 'button']
            ),
        ];
    }
    $heading = $i18n->get('Available reports');

    if (isset($_SESSION['SubscribersPlugin']['result'])) {
        $result = $_SESSION['SubscribersPlugin']['result'];
    }
    unset($_SESSION['SubscribersPlugin']);

    include $view;
}

menu();
