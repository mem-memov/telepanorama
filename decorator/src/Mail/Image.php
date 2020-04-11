<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

use Telepanorama\Partner\Exhibition\Connection as Exhibition;

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
            $remotePath = '/var/www/textures/2.jpg';
            $this->exhibition->deleteOnRemoteServer($remotePath);
            $this->exhibition->sendToRemoteServer($this->path, $remotePath);
            $this->exhibition->deleteOnLocalServer($this->path);

            $this->path = null;
        }
    }
}