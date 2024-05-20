<?php
/*
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

namespace phpList\plugin\SubscribersPlugin;

use Psr\Container\ContainerInterface;

/*
 * This file provides the dependencies for a dependency injection container.
 */

return [
    'phpList\plugin\SubscribersPlugin\Controller\Command' => function (ContainerInterface $container) {
        return new Controller\Command(
            $container->get('phpList\plugin\SubscribersPlugin\DAO\Command'),
            $container->get('phpList\plugin\SubscribersPlugin\Model\Command')
        );
    },
    'phpList\plugin\SubscribersPlugin\Controller\Details' => function (ContainerInterface $container) {
        return new Controller\Details(
            $container->get('phpList\plugin\SubscribersPlugin\Model\Details')
        );
    },
    'phpList\plugin\SubscribersPlugin\Controller\Import2' => function (ContainerInterface $container) {
        $attributeDAO = $container->get('phpList\plugin\Common\DAO\Attribute');

        return new Controller\Import2(
            $attributeDAO->attributesById(),
            $container->get('phpList\plugin\Common\Context')
        );
    },
    'phpList\plugin\SubscribersPlugin\Controller\Reports' => function (ContainerInterface $container) {
        return new Controller\Reports();
    },
    'phpList\plugin\SubscribersPlugin\Controller\Simplereport' => function (ContainerInterface $container) {
        return new Controller\Simplereport(
            $_GET['report'],
            $container->get('phpList\plugin\SubscribersPlugin\ReportFactory'),
            $container->get('phpList\plugin\SubscribersPlugin\DAO\Command')
        );
    },
    'phpList\plugin\SubscribersPlugin\ReportFactory' => function (ContainerInterface $container) {
        return new ReportFactory();
    },
    'phpList\plugin\SubscribersPlugin\Model\Command' => function (ContainerInterface $container) {
        return new Model\Command();
    },
    'phpList\plugin\SubscribersPlugin\Model\Details' => function (ContainerInterface $container) {
        $attributeDAO = $container->get('phpList\plugin\Common\DAO\Attribute');

        return new Model\Details(
            $container->get('phpList\plugin\SubscribersPlugin\DAO\User'),
            $attributeDAO->attributesById(20, 15),
            $container->get('phpList\plugin\Common\DAO\Lists')
        );
    },
    'phpList\plugin\SubscribersPlugin\Model\History' => function (ContainerInterface $container) {
        return new Model\History(
            $container->get('phpList\plugin\SubscribersPlugin\DAO\Event')
        );
    },
    'phpList\plugin\SubscribersPlugin\Model\Subscriptions' => function (ContainerInterface $container) {
        return new Model\Subscriptions(
            $container->get('phpList\plugin\SubscribersPlugin\DAO\Subscriptions')
        );
    },
    'phpList\plugin\SubscribersPlugin\DAO\Command' => function (ContainerInterface $container) {
        return new DAO\Command(
            $container->get('phpList\plugin\Common\DB')
        );
    },
    'phpList\plugin\SubscribersPlugin\DAO\Event' => function (ContainerInterface $container) {
        return new DAO\Event(
            $container->get('phpList\plugin\Common\DB')
        );
    },
    'phpList\plugin\SubscribersPlugin\DAO\Subscriptions' => function (ContainerInterface $container) {
        return new DAO\Subscriptions(
            $container->get('phpList\plugin\Common\DB')
        );
    },
    'phpList\plugin\SubscribersPlugin\DAO\User' => function (ContainerInterface $container) {
        return new DAO\User(
            $container->get('phpList\plugin\Common\DB')
        );
    },
    // these reports each have their own controller
    'report_domains' => function (ContainerInterface $container) {
        return new Controller\Domains(
            $container->get('phpList\plugin\SubscribersPlugin\DAO\Command')
        );
    },
    'phpList\plugin\SubscribersPlugin\Controller\History' => function (ContainerInterface $container) {
        return new Controller\History(
            $container->get('phpList\plugin\SubscribersPlugin\Model\History')
        );
    },
    'phpList\plugin\SubscribersPlugin\Controller\Inactive' => function (ContainerInterface $container) {
        return new Controller\Inactive(
            $container->get('phpList\plugin\SubscribersPlugin\DAO\Command'),
            $container->get('phpList\plugin\Common\Context')
        );
    },
    'report_inactive' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Inactive');
    },
    'report_subscriptions' => function (ContainerInterface $container) {
        return new Controller\Subscriptions(
            $container->get('phpList\plugin\SubscribersPlugin\Model\Subscriptions')
        );
    },
    // these reports all use the simple report controller
    'report_bounce' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
    'report_domainsubscribers' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
    'report_invalid' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
    'report_nolist' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
    'report_unsubscribereason' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
    'report_consecutive' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
    'report_notconfirmed' => function (ContainerInterface $container) {
        return $container->get('phpList\plugin\SubscribersPlugin\Controller\Simplereport');
    },
];
