<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Original
{
    private string $absolutePath;

    public function __construct(
        string $absolutePath
    ) {
        $this->absolutePath = $absolutePath;
    }

    public function getAbsolutePath(): string
    {
        return $this->absolutePath;
    }

    public function createSketchBook(): SketchBook
    {
        return new SketchBook(dirname($this->absolutePath));
    }
}
