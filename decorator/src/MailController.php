<?php

namespace Telepanorama;

use PhpImap\Mailbox;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MailController
{
    public function get(Request $request, Response $response): Response
    {
        $mailbox = new Mailbox(
            '{imap.gmail.com:993/imap/ssl}INBOX', // IMAP server and mailbox folder
            'telepanorama@gmail.com', // Username for the before configured mailbox
            'No276113@', // Password for the before configured username
            '/tmp', // Directory, where attachments will be saved (optional)
            'UTF-8' // Server encoding (optional)
        );

        return $response;
    }
}
