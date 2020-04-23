<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class ComparableAspectRatio
{
    private AspectRatio $aspectRatio;

    public function __construct(
        AspectRatio $aspectRatio
    ) {
        $this->aspectRatio = $aspectRatio;
    }

    public function isEqualTo(ComparableAspectRatio $that): bool
    {
        return $this->aspectRatio->round() === $that->aspectRatio->round();
    }
}