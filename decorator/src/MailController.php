<?php

declare(strict_types=1);

namespace Telepanorama;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Telepanorama\Mail\Picker as MailPicker;

class MailController
{
    private Departments $departments;
    private MailPicker $mailPicker;

    public function __construct(
        Departments $departments,
        MailPicker $mailPicker
    ) {
        $this->departments = $departments;
        $this->mailPicker = $mailPicker;
    }

    public function get(Request $request, Response $response): Response
    {
        $this->mailPicker->assignPackage($this->departments);

        return $response;
    }
}
