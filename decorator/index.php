<?php

require_once __DIR__ . '/vendor/autoload.php';

$siteFactory = new \Telepanorama\Site\Factory();
$telepanorama = $siteFactory->createSite();

$telepanorama->enableErrorReporting();

try {

    $telepanorama->processRequest();

} catch(\Throwable $throwable) {

    $telepanorama->reportUncaughtException($throwable);
}
