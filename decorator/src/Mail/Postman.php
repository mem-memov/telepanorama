<?php

declare(strict_types=1);

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
        $mailsIds = $this->postOffice->searchMailbox();

        if (empty($mailsIds)) {
            return null;
        }

        $mailId = $mailsIds[0];

        return $this->postOffice->handOutPackage($mailId);
    }

    public function throwAwayPackage(Package $package): void
    {
        $this->postOffice->destroyPackage($package);
    }

    public function sendReplyToPackage(Package $package, string $subject, string $message = ''): void
    {
        $this->postOffice->sendMessage($package->getSenderAddress(), $subject, $message);
    }
}
