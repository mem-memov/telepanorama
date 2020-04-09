<?php

namespace Telepanorama\Mail;

use PhpImap\IncomingMail;

class Package
{
    private IncomingMail $incomingMail;

    public function __construct(
        IncomingMail $incomingMail
    ) {
        $this->incomingMail = $incomingMail;
    }

    public function hasSubject(string $subject): bool
    {
        return $subject === $this->incomingMail->subject;
    }

    public function hasAttachment(): bool
    {
        return $this->incomingMail->hasAttachments();
    }
}
