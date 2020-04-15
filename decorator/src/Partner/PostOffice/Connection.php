<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice;

use Telepanorama\Partner\PostOffice\Imap\ReportingConnection as ImapConnection;
use Telepanorama\Partner\PostOffice\Imap\IncomingMail as ImapIncomingMail;
use Telepanorama\Partner\PostOffice\Imap\ServerUnavailable as ImapServerUnavailable;
use Telepanorama\Partner\PostOffice\Smtp\Connection as SmtpConnection;

class Connection
{
    private ImapConnection $imapConnection;
    private SmtpConnection $smtpConnection;

    public function __construct(
        ImapConnection $imapConnection,
        SmtpConnection $smtpConnection
    ) {
        $this->imapConnection = $imapConnection;
        $this->smtpConnection = $smtpConnection;
    }

    /**
     * @throws ImapServerUnavailable
     */
    public function searchMailbox(): array
    {
        return $this->imapConnection->searchMailbox();
    }

    public function getMail(int $mailId): ImapIncomingMail
    {
        return $this->imapConnection->getMail($mailId);
    }

    public function deleteMail(int $mailId): void
    {
        $this->imapConnection->deleteMail($mailId);
    }

    public function sendMessage(string $receiverAddress, string $subject, string $message = ''): void
    {
        $this->smtpConnection->sendMessage($receiverAddress,  $subject, $message);
    }
}