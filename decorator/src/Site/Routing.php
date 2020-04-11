<?php

declare(strict_types=1);

namespace Telepanorama\Site;

use Slim\Interfaces\RouteCollectorProxyInterface;
use Telepanorama\MailController;
use Telepanorama\HelpController;

class Routing
{
    public function createRoutes(RouteCollectorProxyInterface $app): void
    {
        $app->get('/', [MailController::class, 'get']);
        $app->get('/mail', [MailController::class, 'get']);
    }
}
