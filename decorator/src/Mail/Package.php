<?php

namespace Telepanorama\Mail;

use PhpImap\IncomingMail;

class Package
{
    private int $mailId;
    private IncomingMail $incomingMail;

    public function __construct(
        int $mailId,
        IncomingMail $incomingMail
    ) {
        $this->mailId = $mailId;
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

    public function getAttachmentPath(): string
    {
        $files = glob('/tmp/' . $this->mailId . '_*');

        return $files[0];
    }
}
