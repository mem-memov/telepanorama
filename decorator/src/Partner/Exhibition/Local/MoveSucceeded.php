<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Local;

use Telepanorama\Site\Event;
use Telepanorama\Partner\Exhibition\RelativePath;

class MoveSucceeded extends Event
{
    public function __construct(
        string $absoluteOriginPath,
        RelativePath $destinationPath
    ) {
        $this->data['absoluteOriginPath'] = $absoluteOriginPath;
        $this->data['destinationPath'] = $destinationPath->getPath();
    }
}
