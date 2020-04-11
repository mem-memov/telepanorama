<?php

declare(strict_types=1);

namespace Telepanorama\Site;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Throwable;

class Application
{
    private Mode $siteMode;
    private Routing $routing;

    public function __construct(
        Mode $siteMode,
        Routing $routing
    ) {
        $this->siteMode = $siteMode;
        $this->routing = $routing;
    }

    public function enableErrorReporting(): void
    {
        if (!$this->siteMode->isUsedByDeveloper()) {
            return;
        }

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function processRequest(): void
    {
        $container = new Container();
        $app = Bridge::create($container);

        $this->routing->createRoutes($app);

        $app->run();
    }

    public function reportUncaughtException(Throwable $throwable): void
    {
        if (!$this->siteMode->isUsedByDeveloper()) {
            return;
        }

        http_response_code(500);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'errors' => [
                [
                    'type' => 'UNCAUGHT_EXCEPTION',
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString()
                ]
            ]
        ]);
    }
}