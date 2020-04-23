<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Glass
{
    public function diminish(MeasuredOriginal $original, Sketch $sketch): void
    {
        $miniatureImage = imagecreatetruecolor($sketch->getWidth(), $sketch->getHeight());
        $originalImage = imagecreatefromjpeg($original->getAbsolutePath());

        imagecopyresampled(
            $miniatureImage,
            $originalImage,
            0,
            0,
            0,
            0,
            $sketch->getWidth(),
            $sketch->getHeight(),
            $original->getWidth(),
            $original->getHeight()
        );

        imagejpeg($miniatureImage, $sketch->getPath(), 100);
    }
}