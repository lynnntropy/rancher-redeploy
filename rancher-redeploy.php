<?php

if (file_exists(__DIR__.'/../../autoload.php')) {
    require_once __DIR__.'/../../autoload.php';
} else {
    require_once __DIR__.'/vendor/autoload.php';
}

use App\Command\RedeployCommand;
use Symfony\Component\Console\Application;

$application = new Application('rancher-redeploy');

$application->add(new RedeployCommand());
$application->setDefaultCommand('rancher-redeploy', true);

/** @noinspection PhpUnhandledExceptionInspection */
$application->run();