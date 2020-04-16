<?php

declare(strict_types=1);

namespace Telepanorama\Site;

use DateTimeImmutable;
use Exception;

abstract class Event extends Exception
{
    protected array $data = [];

    public function toArray(): array
    {
        return [
            'meta' => [
                'class' => get_class($this),
                'time' => (new DateTimeImmutable())->format(DATE_ATOM)
            ],
            'data' => $this->data
        ];
    }
}
