<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Remote;

use Telepanorama\Site\Event;
use Telepanorama\Partner\Exhibition\RelativePath;

class SendSucceeded extends Event
{
    public function __construct(
        RelativePath $localPath,
        RelativePath $remotePath
    ) {
        $this->data['localPath'] = $localPath->getPath();
        $this->data['remotePath'] = $remotePath->getPath();
    }
}
