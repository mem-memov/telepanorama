<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Local;

use Telepanorama\Site\Event;
use Telepanorama\Partner\Exhibition\RelativePath;

class CreateSucceeded extends Event
{
    public function __construct(
        string $content,
        RelativePath $path
    ) {
        $this->data['content'] = $content;
        $this->data['path'] = $path->getPath();
    }
}
