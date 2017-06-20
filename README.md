# Subscribers Plugin #

## Description ##

The plugin adds pages to the Subscribers menu:

* advanced search - search for subscribers and show subscriber attributes with confirmed and blacklisted status
* subscriber history - shows history events: all, since a start date or those containing specific text
* subscriptions - lists the number of subscriptions and unsubscriptions for each month with a chart showing the data graphically
* subscriber commands - apply a command to a file or group of subcriber email addresses

The plugin also provides a placeholder [LISTUNSUBSCRIBE] to remove a subscriber from the list to which the campaign was sent.

## Installation ##

### Dependencies ###

This plugin is for phplist 3.2.5 or later and now requires php version 5.4 or later. Prior to version 2.6.0 of the plugin, it only required php version 5.3.

It also requires the Common Plugin version 3.6.0 or later to be installed. You should install or upgrade to the latest version. See <https://github.com/bramley/phplist-plugin-common>

### Set the plugin directory ###
You can use a directory outside of the web root by changing the definition of `PLUGIN_ROOTDIR` in config.php.
The benefit of this is that plugins will not be affected when you upgrade phplist.

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

## Known problems ##

### Export fails with "Error, incorrect session token" ###
phplist 3.0.9 had a change that stopped the plugin export working. Instead the message "Error, incorrect session token" is displayed.

To fix this problem upgrade to the latest version of CommonPlugin.

## Donation ##

This plugin is free but if you install and find it useful then a donation to support further development is greatly appreciated.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W5GLX53WDM7T4)

## Version history ##

    version     Description
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
