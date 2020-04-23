<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class RulerOnOriginal
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

    public function measureOriginal(): MeasuredOriginal
    {
        return new MeasuredOriginal(
            $this->original,
            $this->ruler->measureRectangle($this->original->getAbsolutePath())
        );
    }
}