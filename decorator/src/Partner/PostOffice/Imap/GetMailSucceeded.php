<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

use Telepanorama\Site\Event;

class GetMailSucceeded extends Event
{
    private IncomingMail $incomingMail;

    public function __construct(
        IncomingMail $incomingMail
    ) {
        $this->incomingMail = $incomingMail;

        $this->data['subject'] = $incomingMail->getSubject();
        $this->data['senderAddress'] = $incomingMail->getSenderAddress();
    }

    public function getIncomingMail(): IncomingMail
    {
        return $this->incomingMail;
    }
}