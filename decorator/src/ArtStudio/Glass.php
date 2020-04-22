<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Glass
{
    public function diminish(MeasuredPanorama $panorama, Rectangle $rectangle): Image
    {
        $miniature = imagecreatetruecolor($rectangle->getWidth(), $rectangle->getHeight());
        $original = imagecreatefromjpeg($panorama->getAbsolutePath());

        imagecopyresampled(
            $miniature,
            $original,
            0,
            0,
            0,
            0,
            $rectangle->getWidth(),
            $rectangle->getHeight(),
            $panorama->getWidth(),
            $panorama->getHeight()
        );

        $miniatureFilePath = '';
        imagejpeg($miniature, $miniatureFilePath, 100);
    }
}