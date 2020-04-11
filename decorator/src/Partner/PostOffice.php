<?php

declare(strict_types=1);

namespace Telepanorama\Partner;

use PhpImap\Mailbox;

class PostOffice
{
    private ?Mailbox $mailbox = null;

    public function openMailBox(): Mailbox
    {
        if (null === $this->mailbox) {
            $this->mailbox = new Mailbox(
                '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
                'telepanorama.org@gmail.com', // Username for the before configured mailbox
                'No276113@', // Password for the before configured username
                '/tmp', // Directory, where attachments will be saved (optional)
                'UTF-8' // Server encoding (optional)
            );
        }

        return $this->mailbox;
    }
}