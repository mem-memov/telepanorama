<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Sketch
{
    private Rectangle $rectangle;
    private string $absolutePath;

    public function __construct(
        Rectangle $rectangle,
        string $absolutePath
    ) {
        $this->rectangle = $rectangle;
        $this->absolutePath = $absolutePath;
    }

    public function getWidth(): int
    {
        return $this->rectangle->getWidth();
    }

    public function getHeight(): int
    {
        return $this->rectangle->getHeight();
    }

    public function getPath(): string
    {
        return $this->absolutePath;
    }
}