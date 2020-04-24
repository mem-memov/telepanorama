<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use Telepanorama\ArtStudio\Album;

class Showpiece implements WithDescription
{
    private string $file;
    private Album $album;

    public function __construct(
        string $file,
        Album $album
    ) {
        $this->file = $file;
        $this->album = $album;
    }

    public function isEqual(self $another): bool
    {
        return $this->file === $another->file;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getDescription(): array
    {
        return [
            'file' => $this->file,
            'images' => $this->album->getDescription()
        ];
    }
}