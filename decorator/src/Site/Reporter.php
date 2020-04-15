<?php

declare(strict_types=1);

namespace Telepanorama\Site;

class Reporter
{
    private array $events = [];

    public function witness(Event $event): void
    {
        $this->events[] = $event;
    }

    public function write(): string
    {
        return json_encode($this->events);
    }
}
