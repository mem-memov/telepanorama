<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

use PhpImap\Mailbox;
use Telepanorama\Site\Reporter;

class Server
{
    private Reporter $reporter;
    private ?ReportingConnection $connection = null;

    public function __construct(
        Reporter $reporter
    ) {
        $this->reporter = $reporter;
    }

    public function connect(): ReportingConnection
    {
        if (null === $this->connection) {
            $mailbox = new Mailbox(
                '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
                'telepanorama.org@gmail.com', // Username for the before configured mailbox
                'No276113@', // Password for the before configured username
                '/tmp', // Directory, where attachments will be saved (optional)
                'UTF-8' // Server encoding (optional)
            );
            $this->connection = new ReportingConnection(
                new Connection($mailbox),
                $this->reporter
            );
        }

        return $this->connection;
    }
}