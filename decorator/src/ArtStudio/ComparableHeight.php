<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class ComparableHeight
{
    private Height $height;

    public function __construct(
        Height $height
    ) {
        $this->height = $height;
    }

    public function isGreaterThan(ComparableHeight $that): bool
    {
        return $this->height->getPixels() > $that->height->getPixels();
    }
}