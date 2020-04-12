<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice;

use PhpImap\Mailbox;
use Exception;

class Connection
{
    private Mailbox $mailbox;

    public function __construct(
        Mailbox $mailbox
    ) {
        $this->mailbox = $mailbox;
    }

    /**
     * @return array|int[]
     * @throws ServerUnavailable
     */
    public function searchMailbox(): array
    {
        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            return $this->mailbox->searchMailbox('ALL');
        } catch(Exception $exception) {
            throw new ServerUnavailable('IMAP connection failed', 0 , $exception);
        }
    }

    public function getMail(int $mailId): IncomingMail
    {
        return new IncomingMail(
            $this->mailbox->getMail($mailId)
        );
    }

    public function deleteMail(int $mailId): void
    {
        $this->mailbox->deleteMail($mailId);
        $this->mailbox->expungeDeletedMails();
    }
}