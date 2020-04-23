<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Glass
{
    public function diminish(MeasuredOriginal $original, Sketch $sketch): void
    {
        if (!$original->toComparableRectangle()->isGreaterThan($sketch->toComparableRectangle())) {

        }

        $miniatureImage = imagecreatetruecolor($sketch->getWidth(), $sketch->getHeight());
        $originalImage = imagecreatefromjpeg($original->getAbsolutePath());

        $isResampled = imagecopyresampled(
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

        if (!$isResampled) {

        }

        $isSavedToFile = imagejpeg($miniatureImage, $sketch->getPath(), 100);

        if (!$isSavedToFile) {

        }
    }
}