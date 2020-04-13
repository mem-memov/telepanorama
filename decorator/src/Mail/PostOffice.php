<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

use Telepanorama\Partner\PostOffice\Imap\ServerUnavailable as ImapServerUnavailable;
use Telepanorama\Partner\PostOffice\Servers as Partner;

class PostOffice
{
    private Partner $partner;

    public function __construct(
        Partner $partner
    ) {
        $this->partner = $partner;
    }

    /**
     * @return array|int[]
     * @throws ImapServerUnavailable
     */
    public function searchMailbox(): array
    {
        $mailbox = $this->partner->connect();

        return $mailbox->searchMailbox();
    }

    public function handOutPackage(int $mailId): Package
    {
        $mailbox = $this->partner->connect();

        $mail = $mailbox->getMail($mailId);

        return new Package(
            $mailId,
            $mail->getSenderAddress(),
            $mail->getSubject(),
            $mail->hasAttachments()
        );
    }

    public function destroyPackage(Package $package): void
    {
        $mailbox = $this->partner->connect();

        $mailbox->deleteMail($package->getMailId());
    }

    public function sendMessage(string $receiverAddress, string $subject, string $message = ''): void
    {
        $mailbox = $this->partner->connect();

        $mailbox->sendMessage($receiverAddress, $subject, $message);
    }
}
