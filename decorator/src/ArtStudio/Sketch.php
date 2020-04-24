<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

use SplFileInfo;

class Sketch
{
    private Rectangle $rectangle;
    private string $fileName;

    public function __construct(
        Rectangle $rectangle,
        string $fileName
    ) {
        $this->rectangle = $rectangle;
        $this->fileName = $fileName;
    }

    public function getWidth(): Width
    {
        return $this->rectangle->getWidth();
    }

    public function getHeight(): Height
    {
        return $this->rectangle->getHeight();
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function toComparableRectangle(): ComparableRectangle
    {
        return $this->rectangle->toComparableRectangle();
    }

    public function toImage(): Image
    {
        $fileInfo = new SplFileInfo($this->fileName);

        return new Image(
            md5_file($this->fileName),
            $fileInfo->getExtension(),
            $this->rectangle,
            $fileInfo->getSize(),
            mime_content_type($this->fileName)
        );
    }
}