<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Remote;

use Telepanorama\Site\Event;
use Telepanorama\Partner\Exhibition\RelativePath;

class CopySucceeded extends Event
{
    private RelativePath $remoteFilePath;
    private RelativePath $remoteLinkPath;

    public function __construct(
        RelativePath $remoteFilePath,
        RelativePath $remoteLinkPath
    ) {
        $this->data['localFilePath'] = $remoteFilePath->getPath();
        $this->data['remoteLinkPath'] = $remoteLinkPath->getPath();
    }
}
