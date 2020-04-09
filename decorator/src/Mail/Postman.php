<?php

namespace Telepanorama\Mail;

use PhpImap\Exceptions\ConnectionException;

class Postman
{
    private PostOffice $postOffice;

    public function __construct(
        PostOffice $postOffice
    ) {
        $this->postOffice = $postOffice;
    }

    public function bringNextPackage(): ?Package
    {
        $mailbox = $this->postOffice->openMailBox();

        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            $mailsIds = $mailbox->searchMailbox('ALL');
        } catch(ConnectionException $exception) {
            throw new PostOfficeClosed('IMAP connection failed', 0 , $exception);
        }

        if (empty($mailsIds)) {
            return null;
        }

        $mailId = $mailsIds[0];

        $mail = $mailbox->getMail($mailId);

        return new Package($mailId, $mail);
    }
}
