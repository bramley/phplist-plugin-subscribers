# Subscribers Plugin #

## Description ##

The plugin adds pages to the Subscribers menu:

* advanced search - search for subscribers and show subscriber attributes with confirmed and blacklisted status
* subscriber commands - apply a command to a file or group of subcriber email addresses
* subscriber reports - various reports on subscribers
* subscriber history - shows subscriber history events: all, since a start date or those containing specific text

The plugin also provides a placeholder [LISTUNSUBSCRIBE] to remove a subscriber from the list to which the campaign was sent.

## Installation ##

### Dependencies ###

This plugin requires phplist 3.3.2 or later and php version 7 or later.

It also requires the Common Plugin version 3.29.1 or later to be installed.
phplist now includes Common Plugin so you should need only to enable it on the Manage Plugins page.

### Install through phplist ###
Install on the Plugins page (menu Config > Manage Plugins) using the package URL `https://github.com/bramley/phplist-plugin-subscribers/archive/master.zip`.

### Install manually ###
Download the plugin zip file from <https://github.com/bramley/phplist-plugin-subscribers/archive/master.zip>

Expand the zip file, then copy the contents of the plugins directory to your phplist plugins directory.
This should contain

* the file SubscribersPlugin.php
* the directory SubscribersPlugin

## Usage ##

For guidance on using the plugin see the plugin's page within the phplist documentation site <https://resources.phplist.com/plugin/subscribers>

## Donation ##

This plugin is free but if you install and find it useful then a donation to support further development is greatly appreciated.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W5GLX53WDM7T4)

## Version history ##

    version     Description
    2.38.0+20240520 Add menu item to run the subscriber history report
    2.37.3+20240215 Use ListTrait trait in Common plugin for the listuser table
    2.37.2+20240209 Fix error in query moving subscriber between lists
    2.37.1+20231212 Minor internal changes
    2.37.0+20230912 Add Spanish translations, thanks to Adolfo M. Cagigal
    2.36.3+20230906 Use publicUrl() function
    2.36.2+20230821 Use publicBaseUrl() function
    2.36.1+20230726 Correct processing when search term is a regex
    2.36.0+20230725 Treat search term as a regexp or literal text
    2.35.1+20230714 Avoid displaying backslashes in attribute values
    2.35.0+20230529 Show % of all subscribers on Domains report
    2.34.2+20230408 Fix problem with list id when importing file as a remote page, GitHub issue 24
    2.34.1+20230401 Rework the validation of selected attributes
    2.34.0+20230331 Order the results on the Advanced Search page by subscriber fields or attributes
    2.33.2+20230225 Display the "copy results" button only when there are some results
    2.33.1+20230207 Minor internal change
    2.33.0+20230124 Allow ! at start of email address pattern to select addresses that do not match
    2.32.0+20221224 Add command to set attribute value to empty string
    2.31.1+20220921 Fix poor response time copying subscriber results with a large number of subscribers
    2.31.0+20220112 Add remote access page to import subscribers
    2.30.3+20211009 Rework not confirmed report query to be compatible with ONLY_FULL_GROUP_BY
    2.30.2+20210429 Include table name in query that has fixed values
    2.30.1+20210311 Avoid displaying empty note on simple subscriber report
    2.30.0+20210310 Add report to show subscribers who have not confirmed
    2.29.1+20210308 Remove leading/trailing spaces from the entered search value
    2.29.0+20201224 Run inactive report from the command line
    2.28.0+20201219 Copy report results to the command page
    2.27.0+20201202 Support translation of front-end texts
    2.26.0+20201116 Add command to add subscribers to a list
    2.25.1+20201116 Avoid clearing the entered emails or pattern when navigating back to the first page
    2.25.0+20201115 Add command to move subscribers between lists
                    Copy search results to the Command page
    2.24.2+20200416 Display dates using the date format configuration
    2.24.1+20200410 Order the consecutive bounces report by the number of bounces descending
    2.24.0+20200407 Add report of consecutive bounces
    2.23.0+20200315 Add Dutch translations, thanks to Peter Buijs
    2.22.0+20200310 Include lists on the unsubscribe reason report
    2.21.0+20200306 Add page to run the inactive subscribers report from the command line
    2.20.0+20190521 Add report of email domains and report of subscribers for a specific domain
    2.19.1+20190225 Improve performance of searching for subscribers
    2.19.0+20190201 Add report to show subscribers with bounce count > 0
    2.18.2+20181211 On Inactive report link to user page instead of userhistory
    2.18.1+20180714 Fix problem of config settings not being displayed
    2.18.0+20180709 Add command to remove subscribers from all lists to which they belong
    2.17.2+20180623 Internal change that adds dependency on phplist 3.3.2
    2.17.1+20180620 Support negative searching
                    Add foreign key to command line import
    2.17.0+20180528 Improvements to display and search on Advanced Search page
    2.16.3+20180526 Correct filter on Subscriber History report
    2.16.2+20180519 Remove hard-coded table names
    2.16.1+20180403 Avoid dependency on php 7
    2.16.0+20180402 Add report of unsubscribe reasons
    2.15.1+20180228 Change the way that confirmed, unconfirmed and blacklisted are counted on the Subscriptions page
    2.15.0+20180210 Add command line page to import a file
    2.14.0+20180126 Add report of subscribers who do not belong to a list
    2.13.1+20171201 Minor bug fix
    2.13.0+20171111 Add command to confirm subscribers
    2.12.0+20171004 Add command to reset subscriber's bounce count
    2.11.0+20170827 Add command to change subscribers' subscribe page
    2.10.0+20170802 Add command to resend confirmation email
    2.9.4+20170414  Improve display of Subscriptions page with bootlist theme
    2.9.3+20170409  Use new approach for exporting
    2.9.2+20170331  Remove list subscribe/unsubscribe placeholders when email is forwarded
    2.9.1+20170304  Use core phplist help dialog
    2.9.0+20170302  Add report pages
    2.8.1+20170214  Add page to generate UUIDs for subscribers
    2.8.0+20170124  Display and search for real values of checkbox group attributes
                    Support multiple search values
    2.7.4+20170114  Improve German translation
                    Use exact match when searching on checkboxgroup attribute
    2.7.3+20161005  Avoid dependency on mysql 5.7
    2.7.2+20160923  Correct query when using GROUP BY
    2.7.1+20160901  Update translations
    2.7.0+20160706  Add list subscribe placeholder
    2.6.2+20160603  Avoid Excel problem with export file
    2.6.1+20160421  Allow email addresses to be pasted into a text area
    2.6.0+20160330  Added command to validate email addresses
    2.5.0+20160323  Added action to remove from blacklist
    2.4.0+20160317  Add page to apply action to set of subscribers
    2.3.0+20160110  Add placeholder to unsubscribe from a list
    2.2.0+20151025  Show total of campaigns sent
                    Coding standards changes
    2.1.0+20150904  Show totals of campaigns opened and clicked
    2.0.0+20150815  Added dependencies
    2015-05-29      Improved German translation
    2015-05-28      Allow menu items to be translated
    2015-05-17      Include IP address in export of subscriber history
    2015-05-10      Add dependency checks
    2015-03-23      Change to autoload approach
    2015-01-18      Allow searching on user history IP address
    2014-11-17      Shorten keys on config table to allow longer admin ids
    2014-04-18      Search on id and uniqid
    2014-03-11      Search on confirmed/unconfirmed and blacklisted/not blacklisted
    2014-02-12      Allow searching on email address
    2014-02-03      Display as pages instead of tabs
    2014-01-25      Use Google Chart, minor changes
    2013-11-20      Bug fix
    2013-11-05      Improve layout to be similar to core phplist
    2013-10-27      Display each page as a tab
    2013-05-10      Initial version for phplist 2.11.9 converted from 2.10 version
