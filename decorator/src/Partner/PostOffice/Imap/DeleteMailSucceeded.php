<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

use Telepanorama\Site\Event;

class DeleteMailSucceeded extends Event
{
    public function __construct(
        int $mailId
    ) {
        $this->data['mailId'] = $mailId;
    }
}
