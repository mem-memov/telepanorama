<?php

namespace Telepanorama\Site;

use Slim\Interfaces\RouteCollectorProxyInterface;
use Telepanorama\MailController;
use Telepanorama\HelpController;

class Routing
{
    public function createRoutes(RouteCollectorProxyInterface $app): void
    {
        $app->get('/', [HelpController::class, 'get']);
        $app->get('/mail', [MailController::class, 'get']);
    }
}
