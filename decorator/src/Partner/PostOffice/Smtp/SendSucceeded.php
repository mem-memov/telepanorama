<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Smtp;

use Telepanorama\Site\Event;

class SendSucceeded extends Event
{
    public function __construct(
        string $receiverAddress, string $subject, string $message
    ) {
        $this->data['receiverAddress'] = $receiverAddress;
        $this->data['subject'] = $subject;
        $this->data['message'] = $message;
    }
}