<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class ComparableWidth
{
    private Width $width;

    public function __construct(
        Width $width
    ) {
        $this->width = $width;
    }

    public function isGreaterThan(ComparableWidth $that): bool
    {
        return $this->width->getPixels() > $that->width->getPixels();
    }
}