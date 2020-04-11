<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class Showcase implements WithDescription
{
    private string $id;
    private ShowpieceCollection $showPieceCollection;

    public function __construct(
        string $id,
        ShowpieceCollection $showPieceCollection
    ) {
        $this->id = $id;
        $this->showPieceCollection = $showPieceCollection;
    }

    public function getDescription(): array
    {
        return [
            'id' => $this->id,
            'showpieces' => $this->showPieceCollection->getDescription()
        ];
    }
}