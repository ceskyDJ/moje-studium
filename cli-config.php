<?php

declare(strict_types = 1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Load starter for getting ready some stuff
require_once  __DIR__."/src/starter.php";

/**
 * @var $entityManager EntityManager
 * @noinspection PhpUnhandledExceptionInspection
 */
$entityManager = $container->getInstance(EntityManager::class);

return ConsoleRunner::createHelperSet($entityManager);