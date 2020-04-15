<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

use Telepanorama\Site\Reporter;

class ReportingConnection
{
    private Connection $connection;
    private Reporter $reporter;

    public function __construct(
        Connection $connection,
        Reporter $reporter
    ) {
        $this->connection = $connection;
        $this->reporter = $reporter;
    }

    /**
     * @return array|int[]
     * @throws ServerUnavailable
     */
    public function searchMailbox(): array
    {
        try {
            $this->connection->searchMailbox();
        } catch (SearchMailboxSucceeded $event) {
            $this->reporter->witness($event);
            return $event->getMailIds();
        }
    }

    public function getMail(int $mailId): IncomingMail
    {
        try {
            $this->connection->getMail($mailId);
        } catch (GetMailSucceeded $event) {
            $this->reporter->witness($event);
            return $event->getIncomingMail();
        }
    }

    public function deleteMail(int $mailId): void
    {
        try {
            $this->connection->deleteMail($mailId);
        } catch (DeleteMailSucceeded $event) {
            $this->reporter->witness($event);
        }
    }
}
