<?php

namespace phpList\plugin\SubscribersPlugin;

use phpList\plugin\Common\DB;
use phpList\plugin\Common\FrontendTranslator;

class ListSubscription
{
    private $dao;
    private $translator;

    public function __construct()
    {
        global $pagedata, $plugins;

        $this->translator = new FrontendTranslator($pagedata, $plugins['SubscribersPlugin']->coderoot);
        $this->dao = new DAO\ListSubscription(new DB());
    }

    public function listSubscribe()
    {
        if (!(isset($_GET['uid']))) {
            $this->displayResultPage($this->translator->s('A uid must be specified'));

            return;
        }
        $uid = $_GET['uid'];

        if (!(isset($_GET['list']) && ctype_digit($_GET['list']))) {
            $this->displayResultPage($this->translator->s('A numeric list id must be specified'), $uid);

            return;
        }
        $listId = $_GET['list'];

        if (!($user = $this->dao->userByUniqid($uid))) {
            $this->displayResultPage($this->translator->s('Unknown uid %s', $uid), '');

            return;
        }
        $userId = $user['id'];
        $email = $user['email'];

        if (!($list = $this->dao->listById($listId))) {
            $this->displayResultPage($this->translator->s('Unknown list id %d', $listId), $uid);

            return;
        }
        $listName = $list['name'];

        $rowCount = $this->dao->addSubscriberToList($userId, $listId);

        if ($rowCount > 0) {
            $body = $this->translator->s('Subscriber %s has been added to list %s', $email, $listName);
            addUserHistory(
                $email,
                'Added to list',
                "Added to list '$listName'"
            );
        } else {
            $body = $this->translator->s('Subscriber %s already belongs to list %s', $email, $listName);
        }
        $this->displayResultPage($body, $uid);
    }

    public function messageUnsubscribe()
    {
        if (!(isset($_GET['m']) && ctype_digit($_GET['m']))) {
            $this->displayResultPage($this->translator->s('A numeric message id must be specified'));

            return;
        }

        if (!(isset($_GET['uid']))) {
            $this->displayResultPage($this->translator->s('A uid must be specified'));

            return;
        }
        $mid = $_GET['m'];
        $uid = $_GET['uid'];
        $row = $this->dao->userByUniqid($uid);

        if (!$row) {
            $this->displayResultPage($this->translator->s('Unknown uid %s', $uid));

            return;
        }
        $userId = $row['id'];
        $email = $row['email'];
        $removed = array();

        foreach ($this->dao->listsForSubscriberMessage($userId, $mid) as $row) {
            $count = $this->dao->removeSubscriberFromList($userId, $row['listid']);

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
            $joined = implode(', ', $this->wrapInQuotes($removed));
            $result = $this->translator->s('Subscriber %s has been removed from %s', $email, $joined);
        } else {
            $result = $this->translator->s('Subscriber %s has not been removed from any lists', $email);
        }
        $this->displayResultPage($result, $uid);
    }

    private function displayResultPage($result, $uid = '')
    {
        global $pagedata, $PoweredBy;

        $title = $this->translator->s('List subscription');
        $r = htmlspecialchars($result);
        $preferencesUrl = htmlspecialchars(getConfig('preferencesurl') . "&uid=$uid");
        $preferences = $this->translator->s(
            'To change your details and to choose which lists to be subscribed to, visit your <a href="%s">preferences</a> page.',
            $preferencesUrl
        );

        echo <<<END
    <title>$title</title>
    {$pagedata['header']}
    <div class='note'>$r</div>
    <p>$preferences</p>
    $PoweredBy
    {$pagedata['footer']}
END;
    }

    private function wrapInQuotes($array)
    {
        return array_map(
            function ($value) {
                return '"' . $value . '"';
            },
            $array
        );
    }
}
