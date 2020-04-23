<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class SketchBook
{
    private string $directory;
    private array $sketches = [];

    public function __construct(
        string $directory
    ) {
        $this->directory = $directory;
    }

    public function addSketch(Sketch $sketch): SketchInSketchBook
    {
        $index = count($this->sketches);
        $this->sketches[] = $sketch;

        return new SketchInSketchBook(
            $index,
            $this,
            $sketch
        );
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function eachSketch(callable $apply): void
    {
        array_map($apply, $this->sketches);
    }

    public function toAlbum(): Album
    {
        $album = new Album($this->directory);

        $images = array_map(
            function(Sketch $sketch) {
                return $sketch->toImage();
            },
            $this->sketches
        );

        return $album;
    }
}