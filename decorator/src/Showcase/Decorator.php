<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class Decorator
{
    private Exhibition $exhibition;

    public function __construct(
        Exhibition $exhibition
    ) {
        $this->exhibition = $exhibition;
    }

    public function setUpEmptyShowcase(string $inventoryNumber): void
    {
        $showcase = new Showcase(
            $inventoryNumber,
            new ShowpieceCollection()
        );

        $this->exhibition->createShowcase($showcase);
    }
}