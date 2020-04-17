<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use Exception;
use Telepanorama\Partner\Exhibition\ServerUnavailable;

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

    public function recallShowcase(string $inventoryNumber): bool
    {
        try {
            $this->exhibition->findShowcase($inventoryNumber);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @throws ServerUnavailable
     */
    public function addShowpieceToShowcase(string $inventoryNumber, string $panoramaPath): ShowpieceInShowcase
    {
        $showPiece = $this->exhibition->takeShowpiece($inventoryNumber, $panoramaPath);

        $showcase = $this->exhibition->findShowcase($inventoryNumber);
        $showpieceInShowcase = $showcase->addShowpiece($showPiece);
        $this->exhibition->replaceShowcase($showcase);

        return $showpieceInShowcase;
    }
}