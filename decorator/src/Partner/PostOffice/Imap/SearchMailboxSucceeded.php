<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Imap;

use Telepanorama\Site\Event;

class SearchMailboxSucceeded extends Event
{
    private array $mailIds;

    public function __construct(
        array $mailIds
    ) {
        $this->mailIds = $mailIds;

        $this->data['mailIds'] = $mailIds;
    }

    /**
     * @return array|int[]
     */
    public function getMailIds(): array
    {
        return $this->mailIds;
    }
}
