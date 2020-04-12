<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

use Telepanorama\Partner\Exhibition\Connection as Exhibition;
use Telepanorama\Partner\Exhibition\RelativePath;

class Image
{
    private ?string $path;
    private Exhibition $exhibition;

    public function __construct(
        string $path,
        Exhibition $exhibition
    ) {
        $this->path = $path;
        $this->exhibition = $exhibition;
    }

    public function getPublished(): void
    {
        if (null !== $this->path) {
            $remotePath = new RelativePath('/var/www/textures/2.jpg');
            $localPath = new RelativePath($this->path);
            $this->exhibition->deleteOnRemoteServer($remotePath);
            $this->exhibition->sendToRemoteServer($localPath, $remotePath);
            $this->exhibition->deleteOnLocalServer($localPath);

            $this->path = null;
        }
    }
}