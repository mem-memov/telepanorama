<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

use Telepanorama\Partner\PostOffice\ServerUnavailable;
use Telepanorama\Partner\PostOffice\ImapServer as Partner;

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
     * @throws ServerUnavailable
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

    public function sendMessage($receiverAddress, $subject): void
    {

    }
}
