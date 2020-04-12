<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice;

class Servers
{
    public function connect(): Connection
    {
        return new Connection();
    }
}