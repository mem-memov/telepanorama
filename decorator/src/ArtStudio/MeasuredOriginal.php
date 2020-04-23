<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class MeasuredOriginal
{
    private Original $original;
    private Ruler $ruler;

    public function __construct(
        Original $original,
        Ruler $ruler
    ) {
        $this->original = $original;
        $this->ruler = $ruler;
    }

    public function getAbsolutePath(): string
    {
        return $this->original->getAbsolutePath();
    }

    public function getWidth(): int
    {
        $rectangle = $this->ruler->measureRectangle($this->original->getAbsolutePath());

        return $rectangle->getWidth();
    }

    public function getHeight(): int
    {
        $rectangle = $this->ruler->measureRectangle($this->original->getAbsolutePath());

        return $rectangle->getHeight();
    }
}