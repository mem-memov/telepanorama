<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Smtp;

class SmtpServer
{
    public function connect(): Connection
    {
        return new Connection;
    }
}