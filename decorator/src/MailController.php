<?php

namespace Telepanorama;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MailController
{
    public function get(Request $request, Response $response): Response
    {
        return $response;
    }
}
