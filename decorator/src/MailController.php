<?php

declare(strict_types=1);

namespace Telepanorama;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Telepanorama\Mail\Picker as MailPicker;
use Telepanorama\Site\Reporter;

class MailController
{
    private Departments $departments;
    private MailPicker $mailPicker;
    private Reporter $reporter;

    public function __construct(
        Departments $departments,
        MailPicker $mailPicker,
        Reporter $reporter
    ) {
        $this->departments = $departments;
        $this->mailPicker = $mailPicker;
        $this->reporter = $reporter;
    }

    public function get(Request $request, Response $response): Response
    {
        $this->mailPicker->assignPackage($this->departments);

        $report = $this->reporter->write();
        $response->getBody()->write($report);

        return $response->withHeader('Content-type', 'application/json');
    }
}
