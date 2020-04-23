<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class AspectRatio
{
    private Width $width;
    private Height $height;

    public function __construct(
        Width $width,
        Height $height
    ) {
        $this->width = $width;
        $this->height = $height;
    }

    public function round(): float
    {
        return round($this->width->getPixels() / $this->height->getPixels(), 3);
    }

    public function toComparableAspectRatio(): ComparableAspectRatio
    {
        return new ComparableAspectRatio($this);
    }
}