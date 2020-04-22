<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Ruler
{
    public function measureRectangle(string $file): Rectangle
    {
        [$width, $height] = getimagesize($file);

        return new Rectangle($width, $height);
    }
}