<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice;

use Telepanorama\Partner\PostOffice\Imap\Server as ImapServer;
use Telepanorama\Partner\PostOffice\Smtp\Server as SmtpServer;

class Servers
{
    private ImapServer $imapServer;
    private SmtpServer $smtpServer;

    public function __construct(
        ImapServer $imapServer,
        SmtpServer $smtpServer
    ) {
        $this->imapServer = $imapServer;
        $this->smtpServer = $smtpServer;
    }

    public function connect(): Connection
    {
        return new Connection(
            $this->imapServer->connect(),
            $this->smtpServer->connect()
        );
    }
}