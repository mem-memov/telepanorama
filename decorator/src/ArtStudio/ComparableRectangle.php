<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class ComparableRectangle
{
    private ComparableWidth $comparableWidth;
    private ComparableHeight $comparableHeight;
    private ComparableAspectRatio $comparableAspectRatio;

    public function __construct(
        ComparableWidth $comparableWidth,
        ComparableHeight $comparableHeight,
        ComparableAspectRatio $comparableAspectRatio
    ) {
        $this->comparableWidth = $comparableWidth;
        $this->comparableHeight = $comparableHeight;
        $this->comparableAspectRatio = $comparableAspectRatio;
    }

    public function isGreaterThan(ComparableRectangle $that): bool
    {
        return $this->comparableWidth->isGreaterThan($that->comparableWidth)
            && $this->comparableHeight->isGreaterThan($that->comparableHeight);
    }

    public function isOfSameAspectRatio(ComparableRectangle $that): bool
    {
        return $this->comparableAspectRatio->isEqualTo($that->comparableAspectRatio);
    }
}