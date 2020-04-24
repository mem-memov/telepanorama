<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class ImageInAlbum
{
    private Image $image;
    private Album $album;

    public function __construct(
        Image $image,
        Album $album
    ) {
        $this->image = $image;
        $this->album = $album;
    }

    public function getAbsolutePath(): string
    {
        return $this->album->getDirectory() . '/' . $this->image->getFileName();
    }

    public function getFile(): string
    {
        return $this->image->getFileName();
    }
}
