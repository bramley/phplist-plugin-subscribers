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
use function phpList\plugin\Common\publicUrl;

class SubscribersPlugin extends phplistPlugin
{
    const VERSION_FILE = 'version.txt';
    const PLUGIN = 'SubscribersPlugin';
    const LISTSUBSCRIBE_PAGE = 'subscribe';
    const UNSUBSCRIBE_PAGE = 'unsubscribe';
    const UUID_PAGE = 'generateuuids';
    const IMPORT2_PAGE = 'import2';
    const INACTIVE_REPORT_PAGE = 'inactive';

    /*
     *  Private variables
     */
    private $subscribeLinkText;
    private $unsubscribeLinkText;
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
        'command' => array('category' => 'subscribers'),
        'reports' => array('category' => 'subscribers'),
        'history' => array('category' => 'subscribers'),
    );
    public $publicPages = array(self::LISTSUBSCRIBE_PAGE, self::UNSUBSCRIBE_PAGE);
    public $remotePages = [self::IMPORT2_PAGE];
    public $commandlinePluginPages = array(self::UUID_PAGE, self::IMPORT2_PAGE, self::INACTIVE_REPORT_PAGE);
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

        return publicUrl($params);
    }

    private function unsubscribeUrl($messageid, $uid)
    {
        $params = array(
            'p' => self::UNSUBSCRIBE_PAGE,
            'pi' => self::PLUGIN,
            'uid' => $uid,
            'm' => $messageid,
        );

        return publicUrl($params);
    }

    /**
     * Remove placeholders in a message.
     *
     * @param string $content the message content
     *
     * @return string content with placeholders removed
     */
    private function removePlaceholders($content)
    {
        $result = $this->replacePlaceholders(
            $content,
            function (array $matches) {
                return '';
            },
            function (array $matches) {
                return '';
            },
            '',
            ''
        );

        return $result;
    }

    /**
     * Replace placeholders in a message.
     *
     * @param string   $content                  the message content
     * @param callable $listSubscribeCallback    callback to replace the listsubscribe placeholder
     * @param callable $listSubscribeUrlCallback callback to replace the listsubscribeurl placeholder
     * @param string   $listUnsubscribe          replacement text for the listunsubscribe placeholder
     * @param string   $listUnsubscribeUrl       replacement text for the listunsubscribeurl placeholder
     *
     * @return string content with placeholders replaced
     */
    private function replacePlaceholders($content, $listSubscribeCallback, $listSubscribeUrlCallback, $listUnsubscribe, $listUnsubscribeUrl)
    {
        $result = $content;
        $result = preg_replace_callback(
            '/\[LISTSUBSCRIBE:(\d+)]/i',
            $listSubscribeCallback,
            $result
        );
        $result = preg_replace_callback(
            '/\[LISTSUBSCRIBEURL:(\d+)]/i',
            $listSubscribeUrlCallback,
            $result
        );
        $result = str_ireplace(
            array('[LISTUNSUBSCRIBE]', '[LISTUNSUBSCRIBEURL]'),
            array($listUnsubscribe, $listUnsubscribeUrl),
            $result
        );

        return $result;
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
    public function activate()
    {
        $i18n = new phpList\plugin\Common\I18N($this);
        $this->pageTitles = array(
            'details' => $i18n->get('Advanced search'),
            'command' => $i18n->get('Subscriber commands'),
            'reports' => $i18n->get('Subscriber reports'),
            'history' => $i18n->get('Subscriber history'),
        );
        $this->subscribeLinkText = getConfig('subscribers_subscribelinktext');
        $this->unsubscribeLinkText = getConfig('subscribers_linktext');
        $this->attributes = stripslashes(getConfig('subscribers_attributes'));

        parent::activate();
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
            'phpList version 3.3.2 or later' => version_compare(VERSION, '3.3.2') >= 0,
            'Common Plugin version 3.33.0 or later installed' => (
                phpListPlugin::isEnabled('CommonPlugin')
                && version_compare($plugins['CommonPlugin']->version, '3.33.0') >= 0
            ),
            'PHP version 7 or greater' => version_compare(PHP_VERSION, '7') > 0,
        );
    }

    /**
     * Replace placeholders in HTML format message.
     * When a message is being forwarded then remove the placeholders.
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
        if (empty($userdata['uniqid'])) {
            return $this->removePlaceholders($content);
        }
        $unsubscribeUrl = $this->unsubscribeUrl($messageid, $userdata['uniqid']);

        $result = $this->replacePlaceholders(
            $content,
            function (array $matches) use ($userdata) {
                return $this->link(
                    $this->subscribeLinkText,
                    $this->listSubscribeUrl($matches[1], $userdata['uniqid']),
                    $this->attributes
                );
            },
            function (array $matches) use ($userdata) {
                return htmlspecialchars($this->listSubscribeUrl($matches[1], $userdata['uniqid']));
            },
            $this->link($this->unsubscribeLinkText, $unsubscribeUrl, $this->attributes),
            htmlspecialchars($unsubscribeUrl)
        );

        return $result;
    }

    /**
     * Replace placeholders in text format message.
     * When a message is being forwarded then remove the placeholders.
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
        if (empty($userdata['uniqid'])) {
            return $this->removePlaceholders($content);
        }
        $unsubscribeUrl = $this->unsubscribeUrl($messageid, $userdata['uniqid']);
        $result = $this->replacePlaceholders(
            $content,
            function (array $matches) use ($userdata) {
                $subscribeUrl = $this->listSubscribeUrl($matches[1], $userdata['uniqid']);

                return "$this->subscribeLinkText $subscribeUrl";
            },
            function (array $matches) use ($userdata) {
                return $this->listSubscribeUrl($matches[1], $userdata['uniqid']);
            },
            "$this->unsubscribeLinkText $unsubscribeUrl",
            $unsubscribeUrl
        );

        return $result;
    }
}
