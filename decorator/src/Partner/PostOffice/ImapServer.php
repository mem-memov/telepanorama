<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice;

use PhpImap\Mailbox;

class ImapServer
{
    private ?Connection $connection = null;

    public function connect(): Connection
    {
        if (null === $this->connection) {
            $mailbox = new Mailbox(
                '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
                'telepanorama.org@gmail.com', // Username for the before configured mailbox
                'No276113@', // Password for the before configured username
                '/tmp', // Directory, where attachments will be saved (optional)
                'UTF-8' // Server encoding (optional)
            );
            $this->connection = new Connection($mailbox);
        }

        return $this->connection;
    }
}