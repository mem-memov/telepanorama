<?php

namespace Telepanorama;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HelpController
{
    public function get(Request $request, Response $response): Response
    {
        $response->getBody()->write('Help');
        return $response;
    }
}
