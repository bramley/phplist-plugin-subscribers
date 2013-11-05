# Subscribers Plugin #

## Description ##

The plugin adds a page to the Subscribers menu that displays three tabs:

* subscriber attributes - shows each subscriber with attributes, and confirmed and blacklisted status
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
    2013-05-10  Initial version for phplist 2.11.9 converted from 2.10 version
    2013-10-27  Display each page as a tab
    2013-11-05  Improve layout to be similar to core phplist
