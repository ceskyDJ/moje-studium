<?php

/**
 * Starter
 * Start default actions and system base
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 */

declare(strict_types = 1);

use Mammoth\Base\SystemController;
use Mammoth\Config\Configurator;
use Mammoth\Http\Factory\RequestFactory;
use Tracy\Debugger;

// Class autoloading
require_once __DIR__.'/../vendor/autoload.php';

Debugger::enable(Debugger::DEBUG);

// Configs and auto run services
session_start();
mb_internal_encoding("UTF-8");

// Create config manager and configure Tracy
$configurator = new Configurator(__DIR__, "config/base-config.ini", "config/local-config.ini");

// DI container
$container = $configurator->createContainer();

// Start additional services
$configurator->enableTracy($container); // Tracy
$configurator->enableLoader($container); // Class auto-loader

// Some dependencies required to start application
/**
 * @var $runner SystemController
 * @noinspection PhpUnhandledExceptionInspection Class is typing manually and verified by IDE
 */
$runner = $container->getInstance(SystemController::class);
/**
 * @var $requestFactory RequestFactory
 * @noinspection PhpUnhandledExceptionInspection Class is typing manually and verified by IDE
 */
$requestFactory = $container->getInstance(RequestFactory::class);

// Start application
$runner->startSystem($requestFactory->create());