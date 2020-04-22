<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class MeasuredPanorama
{
    private Panorama $panorama;
    private Ruler $ruler;

    public function __construct(
        Panorama $panorama,
        Ruler $ruler
    ) {
        $this->panorama = $panorama;
        $this->ruler = $ruler;
    }

    public function getAbsolutePath(): string
    {
        return $this->panorama->getAbsolutePath();
    }

    public function getWidth(): int
    {
        $rectangle = $this->ruler->measureRectangle($this->panorama->getAbsolutePath());

        return $rectangle->getWidth();
    }

    public function getHeight(): int
    {
        $rectangle = $this->ruler->measureRectangle($this->panorama->getAbsolutePath());

        return $rectangle->getHeight();
    }
}