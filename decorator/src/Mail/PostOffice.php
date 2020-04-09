<?php

namespace Telepanorama\Mail;

use PhpImap\Mailbox;

class PostOffice
{
    public function openMailBox(): Mailbox
    {
        return new Mailbox(
            '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
            'telepanorama.org@gmail.com', // Username for the before configured mailbox
            'No276113@', // Password for the before configured username
            '/tmp', // Directory, where attachments will be saved (optional)
            'UTF-8' // Server encoding (optional)
        );
    }
}
