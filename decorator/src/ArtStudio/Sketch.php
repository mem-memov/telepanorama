<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Sketch
{
    private Rectangle $rectangle;
    private string $absolutePath;

    public function __construct(
        Rectangle $rectangle,
        string $absolutePath
    ) {
        $this->rectangle = $rectangle;
        $this->absolutePath = $absolutePath;
    }

    public function getWidth(): int
    {
        return $this->rectangle->getWidth()->getPixels();
    }

    public function getHeight(): int
    {
        return $this->rectangle->getHeight()->getPixels();
    }

    public function getPath(): string
    {
        return $this->absolutePath;
    }

    public function toComparableRectangle(): ComparableRectangle
    {
        return $this->rectangle->toComparableRectangle();
    }
}