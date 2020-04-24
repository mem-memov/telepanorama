<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class SketchBook
{
    private string $directory;
    private array $sketches = [];

    public function __construct(
        string $directory,
        array $sketches
    ) {
        $this->directory = $directory;
        $this->sketches = $sketches;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function eachSketch(callable $apply): void
    {
        array_map(
            function (Sketch $sketch) use ($apply) {
                $apply(new SketchInSketchBook($sketch, $this));
            },
            $this->sketches
        );
    }

    public function toAlbum(): Album
    {
        $images = array_map(
            function(Sketch $sketch) {
                $image =  $sketch->toImage();
                rename($this->directory . '/' . $sketch->getFileName(), $this->directory . '/' . $image->getFileName());
                return $image;
            },
            $this->sketches
        );

        return new Album($this->directory, $images);
    }
}