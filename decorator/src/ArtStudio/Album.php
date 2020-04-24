<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

use SplFileInfo;

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

    public static function fromArray(string $directory, $albumData): self
    {
        $images = array_map(
            function (array $imageData) {
                $fileInfo = new SplFileInfo($imageData['file']);
                return new Image(
                    $fileInfo->getBasename('.' .$fileInfo->getExtension()),
                    $fileInfo->getExtension(),
                    new Rectangle(
                        new Width($imageData['width']),
                        new Height($imageData['height'])
                    ),
                    $imageData['size'],
                    $imageData['mimeType']
                );
            },
            $albumData
        );

        return new Album($directory, $images);
    }

    public function getDirectory(): string
    {
        return $this->directory;
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

    public function getDescription(): array
    {
        return array_map(
            function (Image $image) {
                return $image->getDescription();
            },
            $this->images
        );
    }
}
