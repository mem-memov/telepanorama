<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Local;

use Telepanorama\Site\Event;
use Telepanorama\Partner\Exhibition\RelativePath;

class DeleteSucceeded extends Event
{
    public function __construct(
        RelativePath $path
    ) {
        $this->data['path'] = $path->getPath();
    }
}
