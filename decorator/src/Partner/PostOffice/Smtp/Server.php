<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Smtp;

use Telepanorama\Site\Reporter;

class Server
{
    private Reporter $reporter;

    public function __construct(
        Reporter $reporter
    ) {
        $this->reporter = $reporter;
    }

    public function connect(): ReportingConnection
    {
        return new ReportingConnection(
            new Connection(),
            $this->reporter
        );
    }
}