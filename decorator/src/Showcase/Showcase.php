<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class Showcase implements WithDescription
{
    private string $inventoryNumber;
    private ShowpieceCollection $showPieceCollection;

    public function __construct(
        string $inventoryNumber,
        ShowpieceCollection $showPieceCollection
    ) {
        $this->inventoryNumber = $inventoryNumber;
        $this->showPieceCollection = $showPieceCollection;
    }

    public function getInventoryNumber(): string
    {
        return $this->inventoryNumber;
    }

    public function getDescription(): array
    {
        return [
            'inventoryNumber' => $this->inventoryNumber,
            'showpieces' => $this->showPieceCollection->getDescription()
        ];
    }

    public function addShowpiece(Showpiece $showpiece): ShowpieceInShowcase
    {
        $this->showPieceCollection->add($showpiece);

        return new ShowpieceInShowcase($showpiece, $this);
    }
}