<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Local;

use Telepanorama\Site\Event;
use Telepanorama\Partner\Exhibition\RelativePath;

class ReadSucceeded extends Event
{
    private string $contents;

    public function __construct(
        RelativePath $path,
        string $contents
    ) {
        $this->contents = $contents;

        $this->data['content'] = $contents;
        $this->data['path'] = $path->getPath();
    }

    public function getContents(): string
    {
        return $this->contents;
    }
}
