<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Ruler
{
    public function measureRectangle(string $file): Rectangle
    {
        $size = getimagesize($file);

        if (false === $size) {

        }

        return new Rectangle($size[0], $size[1]);
    }
}