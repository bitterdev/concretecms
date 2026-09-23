<?php

/* Author: Fabian Bitter (fabian@bitter.de) */

/*
 * Lets Doctrine build a missing entity proxy instead of dying on it.
 *
 * Package::upgradeDatabase() of the core unlinks the proxy file of every package
 * entity, because the package entity manager carries the drivers of all installed
 * packages, and writes them again only after the schema update of the package is
 * through, which takes seconds on the live host. With doctrine_dev_mode off the core
 * leaves Doctrine on AUTOGENERATE_NEVER, so any request in that window which touches
 * an entity reference ends in "Failed opening required .../proxies/__CG__....php".
 * That happened on the live installation on 22.09.2026 at 15:20:04, when the deploy
 * of freshcast_models ran c5:package-update and an ad request for rentroom needed
 * the proxy of Bitter\BamgAds\Entity\AdFormat.
 *
 * AUTOGENERATE_FILE_NOT_EXISTS costs one file_exists() per proxy class and request,
 * and Doctrine writes a generated proxy to a temporary file and renames it, so two
 * requests building the same proxy cannot leave half a file behind. A development
 * installation with doctrine_dev_mode on keeps AUTOGENERATE_ALWAYS.
 */

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\Configuration;

$app->extend(Configuration::class, static function (Configuration $config): Configuration {
    if ((int) $config->getAutoGenerateProxyClasses() === AbstractProxyFactory::AUTOGENERATE_NEVER) {
        $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
    }

    return $config;
});
