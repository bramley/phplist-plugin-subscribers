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
 * Registers the plugin with phplist.
 *
 * @category  phplist
 */
class SubscribersPlugin extends phplistPlugin
{
    const VERSION_FILE = 'version.txt';
    const PLUGIN = 'SubscribersPlugin';
    const LISTSUBSCRIBE_PAGE = 'subscribe';
    const UNSUBSCRIBE_PAGE = 'unsubscribe';
    const UUID_PAGE = 'generateuuids';

    /*
     *  Private variables
     */
    private $subscribeLinkText;
    private $unsubscribeLinkText;
    private $rootUrl;
    private $attributes;

    /*
     *  Inherited variables
     */
    public $name = 'Subscribers Plugin';
    public $enabled = true;
    public $authors = 'Duncan Cameron';
    public $description = 'Provides pages for advanced searching, subscriber history, subscriptions, and subscriber commands.';
    public $topMenuLinks = array(
        'details' => array('category' => 'subscribers'),
        'history' => array('category' => 'subscribers'),
        'subscriptions' => array('category' => 'subscribers'),
        'command' => array('category' => 'subscribers'),
        'reports' => array('category' => 'subscribers'),
    );
    public $publicPages = array(self::LISTSUBSCRIBE_PAGE, self::UNSUBSCRIBE_PAGE);
    public $commandlinePluginPages = array(self::UUID_PAGE);
    public $documentationUrl = 'https://resources.phplist.com/plugin/subscribers';

    /*
     * Private functions
     */
    private function link($linkText, $url, $attributes)
    {
        return sprintf('<a href="%s" %s>%s</a>', htmlspecialchars($url), $attributes, htmlspecialchars($linkText));
    }

    private function listSubscribeUrl($listId, $uid)
    {
        $params = array(
            'p' => self::LISTSUBSCRIBE_PAGE,
            'pi' => self::PLUGIN,
            'uid' => $uid,
            'list' => $listId,
        );

        return $this->rootUrl . '?' . http_build_query($params, '', '&');
    }

    private function unsubscribeUrl($messageid, $uid)
    {
        $params = array(
            'p' => self::UNSUBSCRIBE_PAGE,
            'pi' => self::PLUGIN,
            'uid' => $uid,
            'm' => $messageid,
        );

        return $this->rootUrl . '?' . http_build_query($params, '', '&');
    }

    public function __construct()
    {
        $this->coderoot = dirname(__FILE__) . '/' . self::PLUGIN . '/';
        $this->settings = array(
            'subscribers_subscribelinktext' => array(
              'value' => s('Subscribe'),
              'description' => s('The text of the list subscribe link'),
              'type' => 'text',
              'allowempty' => false,
              'category' => 'Subscription',
            ),
            'subscribers_linktext' => array(
              'value' => s('Unsubscribe from this list'),
              'description' => s('The text of the list unsubscribe link'),
              'type' => 'text',
              'allowempty' => false,
              'category' => 'Subscription',
            ),
            'subscribers_attributes' => array(
              'value' => s(''),
              'description' => s('Additional attributes for the list subscribe and list unsubscribe html &lt;a> elements'),
              'type' => 'text',
              'allowempty' => true,
              'category' => 'Subscription',
            ),
        );
        parent::__construct();
        $this->version = (is_file($f = $this->coderoot . self::VERSION_FILE))
            ? file_get_contents($f)
            : '';
    }

    public function adminmenu()
    {
        return $this->pageTitles;
    }

    /**
     * Use this hook to set translatable text and retrieve config entries.
     */
    public function sendFormats()
    {
        global $plugins, $public_scheme, $pageroot;

        require_once $plugins['CommonPlugin']->coderoot . 'Autoloader.php';
        $i18n = new CommonPlugin_I18N($this);
        $this->pageTitles = array(
            'details' => $i18n->get('Advanced search'),
            'history' => $i18n->get('Subscriber History'),
            'subscriptions' => $i18n->get('Subscriptions'),
            'command' => $i18n->get('Subscriber commands'),
            'reports' => $i18n->get('Subscriber reports'),
            'invalid' => $i18n->get('Invalid emails'),
            'inactive' => $i18n->get('Inactive subscribers'),
        );
        $this->subscribeLinkText = getConfig('subscribers_subscribelinktext');
        $this->unsubscribeLinkText = getConfig('subscribers_linktext');
        $this->attributes = stripslashes(getConfig('subscribers_attributes'));
        $this->rootUrl = sprintf('%s://%s%s/', $public_scheme, getConfig('website'), $pageroot);

        return;
    }

    /**
     * Provide the dependencies for enabling this plugin.
     *
     * @return array
     */
    public function dependencyCheck()
    {
        global $plugins;

        return array(
            'phpList version 3.2.5 or later' => version_compare(VERSION, '3.2.5') >= 0,
            'Common plugin v3 installed' => (
                phpListPlugin::isEnabled('CommonPlugin')
                && preg_match('/\d+\.\d+\.\d+/', $plugins['CommonPlugin']->version, $matches)
                && version_compare($matches[0], '3') > 0
            ),
            'PHP version 5.4.0 or greater' => version_compare(PHP_VERSION, '5.4') > 0,
        );
    }

    /**
     * Replace placeholders in HTML format message.
     *
     * @param int    $messageid   the message id
     * @param string $content     the message content
     * @param string $destination the destination email address
     * @param array  $userdata    the user data values
     *
     * @return string content with placeholders replaced
     */
    public function parseOutgoingHTMLMessage($messageid, $content, $destination, $userdata = null)
    {
        error_reporting(-1);
        $url = $this->unsubscribeUrl($messageid, $userdata['uniqid']);

        $result = str_ireplace(
            array('[LISTUNSUBSCRIBE]', '[LISTUNSUBSCRIBEURL]'),
            array($this->link($this->unsubscribeLinkText, $url, $this->attributes), htmlspecialchars($url)),
            $content
        );

        $result = preg_replace_callback(
            '/\[LISTSUBSCRIBE:(\d+)]/i',
            function (array $matches) use ($userdata) {
                return $this->link(
                    $this->subscribeLinkText,
                    $this->listSubscribeUrl($matches[1], $userdata['uniqid']),
                    $this->attributes
                );
            },
            $result
        );

        $result = preg_replace_callback(
            '/\[LISTSUBSCRIBEURL:(\d+)]/i',
            function (array $matches) use ($userdata) {
                return htmlspecialchars($this->listSubscribeUrl($matches[1], $userdata['uniqid']));
            },
            $result
        );

        return $result;
    }

    /**
     * Replace placeholders in text format message.
     *
     * @param int    $messageid   the message id
     * @param string $content     the message content
     * @param string $destination the destination email address
     * @param array  $userdata    the user data values
     *
     * @return string content with placeholders replaced
     */
    public function parseOutgoingTextMessage($messageid, $content, $destination, $userdata = null)
    {
        $url = $this->unsubscribeUrl($messageid, $userdata['uniqid']);

        $result = str_ireplace(
            array('[LISTUNSUBSCRIBE]', '[LISTUNSUBSCRIBEURL]'),
            array("$this->unsubscribeLinkText $url", $url),
            $content
        );

        $result = preg_replace_callback(
            '/\[LISTSUBSCRIBE:(\d+)]/i',
            function (array $matches) use ($userdata) {
                $url = $this->listSubscribeUrl($matches[1], $userdata['uniqid']);

                return "$this->subscribeLinkText $url";
            },
            $result
        );

        $result = preg_replace_callback(
            '/\[LISTSUBSCRIBEURL:(\d+)]/i',
            function (array $matches) use ($userdata) {
                return $this->listSubscribeUrl($matches[1], $userdata['uniqid']);
            },
            $result
        );

        return $result;
    }
}
