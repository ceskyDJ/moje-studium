<?php

declare(strict_types = 1);

/**
 * Doctrine config file for adding some configurations like own type to Doctrine
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 */

use Doctrine\ORM\EntityManager;

/**
 * @var $entityManager EntityManager
 * @noinspection PhpUnhandledExceptionInspection
 */
$entityManager = $container->getInstance(EntityManager::class);
$doctrineConnection = $entityManager->getConnection();

// Type mappings
$doctrineConnection->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");