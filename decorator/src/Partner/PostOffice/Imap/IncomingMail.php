<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

use PhpImap\IncomingMail as Mail;

class IncomingMail
{
    private Mail $mail;

    public function __construct(
        Mail $mail
    ) {
        $this->mail = $mail;
    }

    public function getSenderAddress(): string
    {
        return $this->mail->fromAddress;
    }

    public function getSubject(): string
    {
        return $this->mail->subject;
    }

    public function hasAttachments(): bool
    {
        return $this->mail->hasAttachments();
    }
}