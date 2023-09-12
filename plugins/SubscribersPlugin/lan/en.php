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

/**
 * This file contains the English text.
 *
 * @category  phplist
 */

/*
 *    Important - this file must be saved in UTF-8 encoding
 *
 */
$lan = array(
    'plugin_title' => 'Subscribers Plugin',
/*  Menu items */
    'Advanced search' => 'Advanced search',
    'Subscriber commands' => 'Subscriber commands',
    'Subscriber reports' => 'Subscriber reports',
/*  Advanced Search page */
    'id' => 'id',
    'lists' => 'lists',
    'email' => 'email',
    'Subscribers' => 'Subscribers',
    'confirmed_heading' => 'Conf',
    'not confirmed' => 'not confirmed',
    'blacklisted_heading' => 'Bl',
    'User is blacklisted' => 'User is blacklisted',
    'Subs page' => 'Subs page',
    'Campaigns' => 'Campaigns',
    'Campaigns sent' => 'Campaigns sent',
    'Campaigns opened' => 'Campaigns opened',
    'Campaigns clicked' => 'Campaigns clicked',
    'Copy results to command' => 'Copy results to command',
/* Subscriber History page */
    'Show events' => 'Show events',
    'All' => 'All',
    'Since' => 'Since',
    'Contains' => 'Contains',
    'Show' => 'Show',
//    controller
    'Filter' => 'Filter',
    'Events' => 'Events',
    'event' => 'event',
    'date' => 'date',
    'summary' => 'summary',
    'detail' => 'detail',
    'IP address' => 'IP address',
/*  Subscriptions page */
    'period' => 'Period',
    'subscriptions' => 'Subscriptions',
    'unconfirmed' => 'Unconfirmed',
    'blacklisted' => 'Blacklisted',
    'active' => 'Active',
    'unsubscriptions' => 'Unsubscriptions',
/*  Action page */
    'Apply command to a group of subscribers' => 'Apply command to a group of subscribers',
    'Action for each subscriber' => 'Action for each subscriber',
    'Confirm' => 'Confirm',
    'Unconfirm' => 'Unconfirm',
    'Blacklist' => 'Blacklist',
    'Unblacklist' => 'Remove from blacklist',
    'Delete' => 'Delete',
    'Add to list' => 'Add to list',
    'Move between lists' => 'Move between lists',
    'Remove from list' => 'Remove from list',
    'Remove from all subscribed lists' => 'Remove from all subscribed lists',
    'Resend confirmation request' => 'Resend confirmation request',
    'Change subscribe page' => 'Change subscribe page',
    'Reset bounce count' => 'Reset bounce count',
    'Empty attribute value' => 'Empty attribute value',
    'Copy/paste a list of email addresses, then click the Process button' => 'Copy/paste a list of email addresses, then click the Process button',
    'Process' => 'Process',
    'Or select a file of email addresses to upload, then click the Upload button' => 'Or select a file of email addresses to upload, then click the Upload button',
    'Upload' => 'Upload',
    'Or enter a partial email address to be matched, then click the Match button' => 'Or enter a partial email address to be matched, then click the Match button',
    'Match' => 'Match',
    'Confirm action and subscribers' => 'Confirm action and subscribers',
    'Review the action and the email addresses, then click Apply or Cancel.' => 'Review the action and the email addresses, then click Apply or Cancel.',
    'Apply' => 'Apply',
    'Cancel' => 'Cancel',
    'emails not entered' => 'emails not entered',
    'no valid email addresses entered' => 'no valid email addresses entered',
    'error_match_not_entered' => 'A partial email address must be entered',
    'error_no_match' => 'No subscribers matching "%s" found',
    'error_no_acceptable' => 'No acceptable subscribers found for the selected command',
    'error_action_not_selected' => 'An action must be selected',
    'upload_error_0' => 'There is no error, the file uploaded with success',
    'upload_error_1' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    'upload_error_2' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    'upload_error_3' => 'The uploaded file was only partially uploaded',
    'upload_error_4' => 'No file was uploaded',
    'upload_error_6' => 'Missing a temporary folder',
    'error_extension' => 'The file extension must be txt or csv',
    'error_empty' => 'file "%s" appears to be empty',
    'history_confirmed' => 'Confirmed',
    'history_unconfirmed' => 'Unconfirmed',
    'history_added' => 'Added to list %s',
    'history_blacklisted' => 'Blacklisted by %s',
    'history_moved' => 'Moved from list %s to list %s',
    'history_removed' => 'Removed from list "%s"',
    'history_removed_all' => 'Removed from %d lists',
    'history_subscribe_page' => 'Subscribe page changed from %d to %d',
    'result_blacklisted' => 'Subscribers blacklisted: %d',
    'result_unblacklisted' => 'Subscribers unblacklisted: %d',
    'result_confirmed' => 'Subscribers confirmed: %d',
    'result_unconfirmed' => 'Subscribers unconfirmed: %d',
    'result_added' => 'Subscribers added to list "%s": %d',
    'result_deleted' => 'Subscribers deleted: %d',
    'result_moved' => 'Subscribers removed from list "%s": %d, added to list "%s": %d',
    'result_removed' => 'Subscribers removed from list "%s": %d',
    'result_removed_all' => 'Subscribers removed from lists to which they are subscribed: %d',
    'result_resent' => 'Confirmation requests resent: %d',
    'result_subscribe_page_changed' => 'Subscribe pages changed: %d',
    'result_bounce_count' => 'Bounce count reset: %d',
    'result_empty_attribute' => 'Attribute "%s" emptied for %d subscribers',
    'Subscriber' => 'Subscriber',
    'Text to prepend to the confirmation request email' => 'Text to prepend to the confirmation request email',
    'resend_prepend' => <<<'END'
Sorry to bother you.
We are cleaning up our database and it appears that you have previously signed up to our mailing list and not confirmed your subscription.
We would like to give you the opportunity to re-confirm your subscription. The instructions on how to confirm are below.
END
    ,
    'Number of subscribers to be processed' => 'Number of subscribers to be processed',
/* Reports */
    'Subscriber history' => 'Subscriber history',
    'Subscriptions' => 'Subscriptions',
    'Subscribers with an invalid email address' => 'Subscribers with an invalid email address',
    'Inactive subscribers' => 'Inactive subscribers',
    'Subscribers who do not belong to a list' => 'Subscribers who do not belong to a list',
    'Unsubscribe reasons' => 'Unsubscribe reasons',
    'Bounce count' => 'Bounce count',
    'Consecutive bounces' => 'Consecutive bounces',
    'Domain subscriber counts' => 'Domain subscriber counts',
    'Subscribers who have not confirmed' => 'Subscribers who have not confirmed',
    'Run' => 'Run',
/* Invalid subscribers */
    'All subscribers have a valid email address' => 'All subscribers have a valid email address',
/* Inactive subscribers */
    'Enter period of inactivity' => 'Enter period of inactivity',
    'Inactivity period' => 'Inactivity period',
    'Number of campaigns' => 'Number of campaigns',
    'Last view' => 'Last view',
    'Recent campaigns' => 'Recent campaigns',
    'Total campaigns' => 'Total campaigns',
    "Invalid interval value '%s'" => "Invalid interval value '%s'",
/* Subscribers who do not belong to a list */
    'All subscribers belong to at least one list' => 'All subscribers belong to at least one list',
/* Unsubscribe reasons */
    'reason' => 'Reason',
/* Domain subscriber counts */
    'Subscribers on domain %s' => 'Subscribers on domain %s',
    'Domain %s does not have any subscribers' => 'Domain %s does not have any subscribers',
);
