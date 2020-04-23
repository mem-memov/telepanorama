<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class MeasuredOriginal
{
    private Original $original;
    private Rectangle $rectangle;

    public function __construct(
        Original $original,
        Rectangle $rectangle
    ) {
        $this->original = $original;
        $this->rectangle = $rectangle;
    }

    public function getAbsolutePath(): string
    {
        return $this->original->getAbsolutePath();
    }

    public function getWidth(): int
    {
        return $this->rectangle->getWidth()->getPixels();
    }

    public function getHeight(): int
    {
        return $this->rectangle->getHeight()->getPixels();
    }

    public function toComparableRectangle(): ComparableRectangle
    {
        return $this->rectangle->toComparableRectangle();
    }
}