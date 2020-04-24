<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

use SplFileInfo;

class SketchInSketchBook
{
    private Sketch $sketch;
    private SketchBook $sketchBook;

    public function __construct(
        Sketch $sketch,
        SketchBook $sketchBook
    ) {
        $this->sketch = $sketch;
        $this->sketchBook = $sketchBook;
    }

    public function getWidth(): int
    {
        return $this->sketch->getWidth()->getPixels();
    }

    public function getHeight(): int
    {
        return $this->sketch->getHeight()->getPixels();
    }

    public function getPath(): string
    {
        return $this->sketchBook->getDirectory() . '/' . $this->sketch->getFileName();
    }

    public function toComparableRectangle(): ComparableRectangle
    {
        return $this->sketch->toComparableRectangle();
    }

    public function toImage(): Image
    {
        $absolutePath = $this->sketchBook->getDirectory() . '/' . $this->sketch->getFileName();
        $fileInfo = new SplFileInfo($absolutePath);

        return new Image(
            md5_file($absolutePath),
            $fileInfo->getExtension(),
            $this->sketch->getRectangle(),
            $fileInfo->getSize(),
            mime_content_type($absolutePath)
        );
    }
}