<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Glass
{
    public function diminish(MeasuredOriginal $original, SketchInSketchBook $sketch): void
    {
        if (false === $original->toComparableRectangle()->isGreaterThan($sketch->toComparableRectangle())) {

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

        if (false === $isResampled) {

        }

        $isOriginalImageDestroyed = imagedestroy($originalImage);

        if (false === $isOriginalImageDestroyed) {

        }

        $isInterlaced = imageinterlace($miniatureImage, true);

        if (false === $isInterlaced) {

        }

        $isSavedToFile = imagejpeg($miniatureImage, $sketch->getPath(), 100);

        if (false === $isSavedToFile) {

        }

        $isMiniatureImageDestroyed = imagedestroy($miniatureImage);

        if (false === $isMiniatureImageDestroyed) {

        }
    }
}