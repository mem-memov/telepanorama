<?php

require_once __DIR__ . '/vendor/autoload.php';

$siteFactory = new \Telepanorama\Site\Factory();
$telepanorama = $siteFactory->createSite(__DIR__ . '/routing.php');

$telepanorama->enableErrorReporting();

try {

    $telepanorama->processRequest();

} catch(\Throwable $throwable) {

    $telepanorama->reportUncaughtException($throwable);
}
