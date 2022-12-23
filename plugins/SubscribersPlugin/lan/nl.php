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
 * This file contains the Dutch NL text.
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
    'Advanced search' => 'Geavanceerd Zoeken',
    'Subscriber commands' => 'Abonnee-Opdrachten',
    'Subscriber reports' => 'Abonnee-Rapporten',
/*  Advanced Search page */
    'id' => 'id',
    'lists' => 'lijsten',
    'email' => 'email',
    'Subscribers' => 'Abonnees',
    'confirmed_heading' => 'Bevestigd',
    'not confirmed' => 'niet bevestigd',
    'blacklisted_heading' => 'Zw',
    'User is blacklisted' => 'Gebruiker staat op de Zwartelijst',
    'Subs page' => 'Abn pag.',
    'Campaigns' => 'Verzendlijsten',
    'Campaigns sent' => 'Berichten verzonden',
    'Campaigns opened' => 'Berichten geopened',
    'Campaigns clicked' => 'Berichten geklikt',
/* Subscriber History page */
    'Show events' => 'Toon meldingen',
    'All' => 'Alle',
    'Since' => 'Sinds',
    'Contains' => 'Bevat',
    'Show' => 'Toon',
//    controller
    'Filter' => 'Filter',
    'Events' => 'Meldingen',
    'event' => 'melding',
    'date' => 'Datum',
    'summary' => 'samenvatting',
    'detail' => 'detail',
    'IP address' => 'IP adres',
/*  Subscriptions page */
    'period' => 'Periode',
    'subscriptions' => 'Abonnees',
    'unconfirmed' => 'Niet bevestigd',
    'blacklisted' => 'Op Zwartelijst',
    'active' => 'Actief',
    'unsubscriptions' => 'Afmeldingen',
/*  Action page */
    'Apply command to a group of subscribers' => 'Pas toe op een groep Abonnees',
    'Action for each subscriber' => 'Actie voor elke Abonnee',
    'Confirm' => 'Bevestig',
    'Unconfirm' => 'Maak nietbevestigd',
    'Blacklist' => 'Zwartelijst',
    'Unblacklist' => 'Verwijder van Zwartelijst',
    'Delete' => 'Wis',
    'Remove from list' => 'Verwijder van lijst',
    'Remove from all subscribed lists' => 'Verwijder van alle geabonneerde lijsten',
    'Resend confirmation request' => 'Verstuur verzoek om bevestiging opnieuw',
    'Change subscribe page' => 'Verander abonnee pagina',
    'Reset bounce count' => 'Zet aantal Teruggekaatst op 0',
    'Copy/paste a list of email addresses, then click the Process button' => 'Kopieer/plak een lijst email adressen en klik dan Verwerk',
    'Process' => 'Verwerk',
    'Or select a file of email addresses to upload, then click the Upload button' => 'Of selecteer een bestand met email adressen en klik Laden',
    'Upload' => 'Laden',
    'Or enter a partial email address to be matched, then click the Match button' => 'Of geef een deel van een email adres om te vergelijken en klik dan Vergelijk',
    'Match' => 'Vergelijk',
    'Confirm action and subscribers' => 'Bevestig actie en abonnees',
    'Review the action and the email addresses, then click Apply or Cancel.' => 'Beoordeel de actie en de email adressen en klik dan Toepassen of Stop.',
    'Apply' => 'Toepassen',
    'Cancel' => 'Stop',
    'emails not entered' => 'geen emails beschikbaar',
    'no valid email addresses entered' => 'geen geldig email addresses',
    'error_match_not_entered' => 'Er moet deel van een email adres gegeven worden',
    'error_no_match' => 'Geen Abonnees "%s" gevonden',
    'error_no_acceptable' => 'Geen Abonnees gevonden voor deze bewerking',
    'upload_error_0' => 'Geen fouten, het bestand is correct geladen',
    'upload_error_1' => 'Het bestand is groter dan de upload_max_filesize instelling in php.ini',
    'upload_error_2' => 'Het bestand is groter dan de MAX_FILE_SIZE instelling dat in de HTML form staat',
    'upload_error_3' => 'Het bestand is maar voor een deel geladen',
    'upload_error_4' => 'Er is geen bestand geladen',
    'upload_error_6' => 'De temp map mist',
    'error_extension' => 'De bestand extensie moet txt of csv zijn',
    'error_empty' => 'bestand "%s" is leeg',
    'history_confirmed' => 'Bevestigd',
    'history_unconfirmed' => 'Niet Bevestigd',
    'history_blacklisted' => 'Op Zwartelijst door %s',
    'history_removed' => 'Verwijderd van lijst "%s"',
    'history_removed_all' => 'Verwijderd van %d lijsten',
    'history_subscribe_page' => 'Abonnee pagina veranderd van %d naar %d',
    'result_blacklisted' => 'Abonnees op Zwartelijt: %d',
    'result_unblacklisted' => 'Abonnees niet op Zwartelijst: %d',
    'result_confirmed' => 'Abonnees bevestigd: %d',
    'result_unconfirmed' => 'Abonnees niet bevestigd: %d',
    'result_deleted' => 'Abonnees verwijderd: %d',
    'result_removed' => 'Abonnees verwijder van de lijst "%s": %d',
    'result_removed_all' => 'Abonnees verwijder van de lijsten waar ze abonnee van waren: %d',
    'result_resent' => 'Bevestigings verzoek opnieuw verzonden: %d',
    'result_subscribe_page_changed' => 'Inschrijf pagina veranderd: %d',
    'result_bounce_count' => 'Teruggekaatst teller op nul gezet: %d',
    'result_empty_attribute' => 'Attribute "%s" emptied for %d subscribers',
    'Subscriber' => 'Abonnee',
    'Text to prepend to the confirmation request email' => 'Tekst voor de email voor bevestiging',
    'resend_prepend' => <<<'END'
Mogen we even storen.
We zijn aan het opruimen en het blijkt dat je voorheen eens aangemeld hebt voor een van onze lijsten maar nooit een bevestiging hebt verzonden.
Nu krijg je de kans om alsnog te bevestiging te sturen. Hoe dat gaat zie je hieronder.
END
    ,
    'Number of subscribers to be processed' => 'Aantal Abonnees verwerkt',
/* Reports */
    'Subscriber history' => 'Abonnee Historie',
    'Subscriptions' => 'Abonnees',
    'Subscribers with an invalid email address' => 'Abonnees met een ongeldig email adres',
    'Inactive subscribers' => 'Abonnees niet actief',
    'Subscribers who do not belong to a list' => 'Abonnees die nergens voor ingeschreven staan',
    'Unsubscribe reasons' => 'Reden van uitschrijven',
    'Bounce count' => 'Aantal keer Teruggekaatst',
    'Domain subscriber counts' => 'Aantal Abonnees per Domain',
    'Run' => 'Verwerk',
/* Invalid subscribers */
    'All subscribers have a valid email address' => 'Alle Abonnes hebben een geldig email adres',
/* Inactive subscribers */
    'Enter period of inactivity' => 'Geef een inactieve periode',
    'Inactivity period' => 'Inactieve periode',
    'Number of campaigns' => 'Aantal berichten',
    'Last view' => 'Meest recente overzicht',
    'Recent campaigns' => 'Recente berichten',
    'Total campaigns' => 'Totaal berichten',
    "Invalid interval value '%s'" => "Ongeldige interval waarde '%s'",
/* Subscribers who do not belong to a list */
    'All subscribers belong to at least one list' => 'Alle Abonnees hebben minstens voor 1 lijst ingeschreven',
/* Unsubscribe reasons */
    'reason' => 'Reden',
/* Domain subscriber counts */
    'Subscribers on domain %s' => 'Abonnees van domain %s',
    'Domain %s does not have any subscribers' => 'Domain %s heeft geen enkele Abonnee',
);
