<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Rectangle
{
    private int $width;
    private int $height;

    public function __construct(
        int $width,
        int $height
    ) {
        $this->width = $width;
        $this->height = $height;
    }

    public function getAspectRatio(): float
    {
        return round($this->width / $this->height, 3);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}