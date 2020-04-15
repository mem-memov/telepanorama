<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

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
     * @throws SearchMailboxSucceeded
     * @throws ServerUnavailable
     */
    public function searchMailbox(): void
    {
        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            $mailIds = $this->mailbox->searchMailbox('ALL');
        } catch(Exception $exception) {
            throw new ServerUnavailable('IMAP connection failed', 0 , $exception);
        }

        throw new SearchMailboxSucceeded($mailIds);
    }

    /**
     * @throws GetMailSucceeded
     */
    public function getMail(int $mailId): IncomingMail
    {
        $incomingMail = new IncomingMail(
            $this->mailbox->getMail($mailId)
        );

        throw new GetMailSucceeded($incomingMail);
    }

    /**
     * @throws DeleteMailSucceeded
     */
    public function deleteMail(int $mailId): void
    {
        $this->mailbox->deleteMail($mailId);
        $this->mailbox->expungeDeletedMails();

        throw new DeleteMailSucceeded($mailId);
    }
}