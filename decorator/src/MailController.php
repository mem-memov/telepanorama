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
            '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
            'telepanorama.org@gmail.com', // Username for the before configured mailbox
            'No276113@', // Password for the before configured username
            '/tmp', // Directory, where attachments will be saved (optional)
            'UTF-8' // Server encoding (optional)
        );

        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            $mailsIds = $mailbox->searchMailbox('ALL');
        } catch(PhpImap\Exceptions\ConnectionException $ex) {
            echo "IMAP connection failed: " . $ex;
            die();
        }
// If $mailsIds is empty, no emails could be found
        if(!$mailsIds) {
            die('Mailbox is empty');
        }

// Get the first message
// If '__DIR__' was defined in the first line, it will automatically
// save all attachments to the specified directory
        $mail = $mailbox->getMail($mailsIds[0]);

// Show, if $mail has one or more attachments
        echo "\nMail has attachments? ";
        if($mail->hasAttachments()) {
            echo "Yes\n";
        } else {
            echo "No\n";
        }

// Print all information of $mail
        echo $mail->subject . "\n";

// Print all attachements of $mail
        echo "\n\nAttachments:\n";
//        print_r($mail->getAttachments());

        return $response;
    }
}
