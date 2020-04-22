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
}