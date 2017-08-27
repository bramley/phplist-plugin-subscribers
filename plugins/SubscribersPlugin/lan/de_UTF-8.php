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
 * This file contains the German translations of the English text.
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
    'Advanced search' => 'Erweiterte Suche',
    'Subscriber History' => 'Abonnenten-Historie',
    'Subscriptions' => 'Abonnements',
    'Subscriber commands' => 'Abonnenten-Befehle',
    'Subscriber reports' => 'Subscriber reports',
/*  Advanced Search page */
    'id' => 'id',
    'lists' => 'Listen',
    'email' => 'E-Mail',
    'Subscribers' => 'Abonnenten',
    'confirmed_heading' => 'Bestät.',
    'not confirmed' => 'Unbestätigt',
    'blacklisted_heading' => 'Gesp.',
    'User is blacklisted' => 'Benutzer ist gesperrt (Blacklist)',
    'Campaigns' => 'Nachrichten',
    'Campaigns sent' => 'Versendete Nachrichten',
    'Campaigns opened' => 'Geöffnete Nachrichten',
    'Campaigns clicked' => 'Angeklickte Nachrichten',
/* Subscriber History page */
    'Show events' => 'Ereignisse anzeigen',
    'All' => 'Alle',
    'Since' => 'Seit',
    'Contains' => 'Enthält',
    'Show' => 'Anzeigen',
//    controller
    'Filter' => 'Filter',
    'Events' => 'Ereignisse',
    'event' => 'Ereignis',
    'date' => 'Datum',
    'summary' => 'Zusammenfassung',
    'detail' => 'Details',
    'IP address' => 'IP-Adresse',
/*  Subscriptions page */
    'period' => 'Zeitraum',
    'subscriptions' => 'Abonnements',
    'unconfirmed' => 'Unbestätigt',
    'blacklisted' => 'Gesperrt (Blacklist)',
    'active' => 'Aktiv',
    'unsubscriptions' => 'Abbestellungen',
/*  Action page */
    'Apply command to a group of subscribers' => 'Befehl auf eine Gruppe von Abonnenten anwenden',
    'Action for each subscriber' => 'Befehl für jeden Abonnenten',
    'Resend confirmation request' => 'Resend confirmation request',
    'Unconfirm' => 'Als unbestätigt markieren',
    'Blacklist' => 'Sperren (auf die Blacklist setzen)',
    'Unblacklist' => 'Sperrung aufheben (von der Blacklist entfernen)',
    'Delete' => 'Löschen',
    'Remove from list' => 'Entfernen von der Liste',
    'Copy/paste a list of email addresses, then click the Process button' => 'Fügen Sie per "Copy & Paste" eine Liste von E-Mail-Adressen ein und klicken Sie dann auf "Ausführen"',
    'Process' => 'Ausführen',
    'Or select a file of email addresses to upload, then click the Upload button' => 'Oder wählen Sie eine Datei mit E-Mail-Adressen zum Hochladen aus und klicken Sie dann auf "Hochladen"',
    'Upload' => 'Hochladen',
    'Or enter a partial email address to be matched, then click the Match button' => 'Oder geben Sie eine Zeichenfolge ein, mit der die E-Mail-Adressen der Abonnenten abgeglichen werden sollen, und klicken Sie dann auf "Abgleichen"',
    'Match' => 'Abgleichen',
    'Confirm action and subscribers' => 'Bestätigen Sie die Aktion und die Abonnenten',
    'Review the action and the email addresses, then click Apply or Cancel.' => 'Überprüfen Sie die Aktion und die E-Mail-Adressen und klicken Sie dann "Anwenden" oder "Abbrechen"',
    'Apply' => 'Anwenden',
    'Cancel' => 'Abbrechen',
    'emails not entered' => 'Keine E-Mail-Adressen eingegeben',
    'no valid email addresses entered' => 'Keine gültigen E-Mail-Adressen eingegeben',
    'error_match_not_entered' => 'Geben Sie eine Zeichenfolge ein, mit der die E-Mail-Adressen der Abonnenten abgeglichen werden sollen',
    'error_no_match' => 'Keine Abonnenten mit "%s" gefunden',
    'error_no_acceptable' => 'No acceptable subscribers found for the selected command',
    'upload_error_0' => 'Kein Fehler, Datei erfolgreich hochgeladen',
    'upload_error_1' => 'Die hochgeladene Datei überschreitet die upload_max_filesize Direktive in php.ini',
    'upload_error_2' => 'Die hochgeladene Datei überschreitet die im HTML-Formular angegebene MAX_FILE_SIZE Direktive',
    'upload_error_3' => 'Die hochgeladene Datei wurde nur teilweise hochgeladen',
    'upload_error_4' => 'Es wurde keine Datei hochgeladen',
    'upload_error_6' => 'Es fehlt ein temporärer Ordner',
    'error_extension' => 'Die Datei-Endung muss txt oder csv sein',
    'error_empty' => 'Die Datei "%s" scheint leer zu sein',
    'history_unconfirmed' => 'Als unbestätigt markiert',
    'history_blacklisted' => 'Gesperrt von %s',
    'history_removed' => 'Abgemeldet von der Liste "%s"',
    'history_subscribe_page' => 'Subscribe page changed from %d to %d',
    'result_blacklisted' => 'Gesperrte Abonnenten: %d',
    'result_unblacklisted' => 'Abonnenten mit aufgehobener Sperrung: %d',
    'result_unconfirmed' => 'Als unbestätigt markierte Abonnenten: %d',
    'result_deleted' => 'Gelöschte Abonnenten: %d',
    'result_removed' => 'Von der Liste "%s" abgemeldete Abonnenten: %d',
    'result_resent' => 'Confirmation requests resent: %d',
    'result_subscribe_page_changed' => 'Subscribe pages changed: %d',
    'Subscriber' => 'Abonnent',
    'Text to prepend to the confirmation request email' => 'Text to prepend to the confirmation request email',
    'resend_prepend' => <<<'END'
Sorry to bother you.
We are cleaning up our database and it appears that you have previously signed up to our mailing list and not confirmed your subscription.
We would like to give you the opportunity to re-confirm your subscription. The instructions on how to confirm are below.
END
    ,
/* Reports */
    'Available reports' => 'Available reports',
    'Subscribers with an invalid email address' => 'Abonnenten mit ungültiger E-Mail-Adresse',
    'Inactive subscribers' => 'Inactive subscribers',
    'Run' => 'Run',
/* Invalid subscribers */
    'Invalid emails' => 'Invalid emails',
    'All subscribers have a valid email address' => 'Alle Abonnenten haben eine gültige E-Mail-Adresse',
/* Inactive subscribers */
    'Display inactive subscribers' => 'Display inactive subscribers',
    'Inactivity period' => 'Inactivity period',
    'Number of campaigns' => 'Number of campaigns',
    'Last view' => 'Last view',
    'Recent campaigns' => 'Recent campaigns',
    'Total campaigns' => 'Total campaigns',
    "Invalid interval value '%s'" => "Invalid interval value '%s'",
);
