<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class ShowpieceInShowcase
{
    private Showpiece $showpiece;
    private Showcase $showcase;

    public function __construct(
        Showpiece $showpiece,
        Showcase $showcase
    ) {
        $this->showpiece = $showpiece;
        $this->showcase = $showcase;
    }

    public function getUrl(): string
    {
        return $this->showpiece->getUrl($this->showcase->getInventoryNumber());
    }
}
