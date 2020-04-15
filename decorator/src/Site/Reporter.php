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
        $data = array_map(
            function (Event $event) {
                return $event->toArray();
            },
            $this->events
        );
        return json_encode($data);
    }
}
