<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

class Package
{
    private int $mailId;
    private string $senderAddress;
    private string $subject;
    private bool $hasAttachments;

    public function __construct(
        int $mailId,
        string $senderAddress,
        string $subject,
        bool $hasAttachments
    ) {
        $this->mailId = $mailId;
        $this->senderAddress = $senderAddress;
        $this->subject = $subject;
        $this->hasAttachments = $hasAttachments;
    }

    public function getMailId(): int
    {
        return $this->mailId;
    }

    public function getSenderAddress(): string
    {
        return $this->senderAddress;
    }

    public function hasSubjectCaseInsensitive(string $subject): bool
    {
        return strtolower($subject) === strtolower($this->subject);
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function hasAttachment(): bool
    {
        return $this->hasAttachments;
    }

    public function getAttachmentPath(): string
    {
        $files = glob('/tmp/' . $this->mailId . '_*');

        return $files[0];
    }
}
