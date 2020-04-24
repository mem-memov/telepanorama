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

        return new Rectangle(new Width($size[0]), new Height($size[1]));
    }

    public function putOnOriginal(Original $original): RulerOnOriginal
    {
        return new RulerOnOriginal(
            $original,
            $this
        );
    }
}