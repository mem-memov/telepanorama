<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Album
{
    private string $directory;
    private array $images;

    public function __construct(
        string $directory,
        array $images
    ) {
        $this->directory = $directory;
        $this->images = $images;
    }

    public function eachImage(callable $apply): void
    {
        array_map(
            function (Image $image) use ($apply){
                $apply(new ImageInAlbum($image, $this));
            },
            $this->images
        );
    }
}
