<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Rectangle
{
    private Width $width;
    private Height $height;

    public function __construct(
        Width $width,
        Height $height
    ) {
        $this->width = $width;
        $this->height = $height;
    }

    public function getAspectRatio(): AspectRatio
    {
        return new AspectRatio($this->width, $this->height);
    }

    public function getWidth(): Width
    {
        return $this->width;
    }

    public function getHeight(): Height
    {
        return $this->height;
    }

    public function toComparableRectangle(): ComparableRectangle
    {
        return new ComparableRectangle(
            $this->width->toComparableWidth(),
            $this->height->toComparableHeight(),
            $this->getAspectRatio()->toComparableAspectRatio()
        );
    }
}