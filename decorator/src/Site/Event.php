<?php

declare(strict_types=1);

namespace Telepanorama\Site;

use Exception;

abstract class Event extends Exception
{
    protected array $data = [];

    public function toArray(): array
    {
        return $this->data;
    }
}
