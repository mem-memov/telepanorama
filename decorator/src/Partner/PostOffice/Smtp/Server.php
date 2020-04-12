<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Smtp;

class Server
{
    public function connect(): Connection
    {
        return new Connection();
    }
}