<?php

namespace Telepanorama;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Telepanorama\Mail\Receiver;

class MailController
{
    private Receiver $reseiver;

    public function __construct(
        Receiver $reseiver
    ) {
        $this->reseiver = $reseiver;
    }

    public function get(Request $request, Response $response): Response
    {
        $this->reseiver->keepImage();
        return $response;
    }
}
