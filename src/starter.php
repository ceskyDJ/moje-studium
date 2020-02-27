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

// Start Tracy before everything else
// Unfortunately it hasn't been configured yet but there could be only bad
// config file address specified
// If Tracy can't write log, it'll be caused by this problem, probably
Debugger::enable();

// Configs and auto run services
session_start();
mb_internal_encoding("UTF-8");

// Create config manager and configure Tracy
$configurator = new Configurator(__DIR__, "config/base-config.ini", "config/local-config.ini");

// Enable Tracy
$configurator->enableTracy("admin@ceskydj.cz");

// DI container
$container = $configurator->createContainer();

// Start and port configure additional services
$configurator->enableLoader($container); // Class auto-loader
$configurator->setupAdditionalTracySettings($container); // Tracy

// Configure Doctrine (own types etc.)
require_once __DIR__.'/doctrine-config.php';

// Application running
// Cli can't have these things activated
if ($configurator->isCli() === false) {
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

    $request = $requestFactory->create();

    // Start application
    $runner->startSystem($request);
}