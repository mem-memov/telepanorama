<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Width
{
    private int $pixels;

    public function __construct(
        int $pixels
    ) {
        $this->pixels = $pixels;
    }

    public function getPixels(): int
    {
        return $this->pixels;
    }

    public function toComparableWidth(): ComparableWidth
    {
        return new ComparableWidth($this);
    }
}