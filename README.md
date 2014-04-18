# Subscribers Plugin #

## Description ##

The plugin adds pages to the Subscribers menu:

* advanced search - search for subscribers and show subscriber attributes with confirmed and blacklisted status
* subscriber history - shows history events: all, since a start date or those containing specific text
* subscriptions - lists the number of subscriptions and unsubscriptions for each month with a chart showing the data graphically

## Installation ##

### Dependencies ###

This plugin is for phplist 3.0.0 and later.

Requires php version 5.2 or later.

Requires the Common Plugin to be installed. 

See <https://github.com/bramley/phplist-plugin-common>

### Set the plugin directory ###
You can use a directory outside of the web root by changing the definition of `PLUGIN_ROOTDIR` in config.php.
The benefit of this is that plugins will not be affected when you upgrade phplist.

### Install through phplist ###
Install on the Plugins page (menu Config > Plugins) using the package URL `https://github.com/bramley/phplist-plugin-subscribers/archive/master.zip`.

In phplist releases 3.0.5 and earlier there is a bug that can cause a plugin to be incompletely installed on some configurations (<https://mantis.phplist.com/view.php?id=16865>). 
Check that these files are in the plugin directory. If not then you will need to install manually. The bug has been fixed in release 3.0.6.

* the file SubscribersPlugin.php
* the directory SubscribersPlugin

Then click the small orange icon to enable the plugin.

### Install manually ###
Download the plugin zip file from <https://github.com/bramley/phplist-plugin-subscribers/archive/master.zip>

Expand the zip file, then copy the contents of the plugins directory to your phplist plugins directory.
This should contain

* the file SubscribersPlugin.php
* the directory SubscribersPlugin

## Donation ##

This plugin is free but if you install and find it useful then a donation to support further development is greatly appreciated.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=W5GLX53WDM7T4)

## Version history ##

    version     Description
    2014-04-18  Search on id and uniqid
    2014-03-11  Search on confirmed/unconfirmed and blacklisted/not blacklisted
    2014-02-12  Allow searching on email address
    2014-02-03  Display as pages instead of tabs
    2014-01-25  Use Google Chart, minor changes
    2013-11-20  Bug fix
    2013-11-05  Improve layout to be similar to core phplist
    2013-10-27  Display each page as a tab
    2013-05-10  Initial version for phplist 2.11.9 converted from 2.10 version
