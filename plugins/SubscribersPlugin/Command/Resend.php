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

namespace phpList\plugin\SubscribersPlugin\Command;

/**
 *  This class implements the command to resend a confirmation request email.
 */
class Resend extends Base
{
    /**
     * Constructs and sends a confirmation request email.
     * Copied from phplist file reconcileusers.php.
     *
     * @param array $userdata user data
     *
     * @return bool whether email was sent successfully
     */
    private function resendConfirm(array $userdata)
    {
        global $tables, $envelope;

        $id = $userdata['id'];
        $lists_req = Sql_Query(
            "SELECT l.name
            FROM {$tables['list']} l
            JOIN {$tables['listuser']} lu ON l.id = lu.listid
            WHERE lu.userid = $id"
        );
        $lists = '';

        while ($row = Sql_Fetch_Row($lists_req)) {
            $lists .= '  * '.$row[0]."\n";
        }

        if ($userdata['subscribepage']) {
            $subscribemessage = str_replace(
                '[LISTS]',
                $lists,
                getUserConfig('subscribemessage:' . $userdata['subscribepage'], $id)
            );
            $subject = getConfig('subscribesubject:' . $userdata['subscribepage']);
        } else {
            $subscribemessage = str_replace('[LISTS]', $lists, getUserConfig('subscribemessage', $id));
            $subject = getConfig('subscribesubject');
        }
        logEvent($GLOBALS['I18N']->get('Resending confirmation request to') . ' ' . $userdata['email']);

        return sendMail(
            $userdata['email'],
            $subject,
            $subscribemessage,
            system_messageheaders($userdata['email']),
            $envelope
        );
    }

    public function accept(array $user)
    {
        return $user['confirmed'] == 0;
    }

    public function process(array $user)
    {
        return $this->resendConfirm($user);
    }

    public function result($count)
    {
        return $this->i18n->get('result_resent', $count);
    }
}
