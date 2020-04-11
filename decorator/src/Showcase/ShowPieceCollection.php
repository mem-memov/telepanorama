<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class ShowPieceCollection implements WithDescription
{
    private array $showPieces = [];

    public function add(ShowPiece $showPiece): void
    {

    }

    public function remove(ShowPiece $showPiece): void
    {

    }

    public function iterate(callable $use): void
    {
        foreach ($this->showPieces as $showPiece) {
            $use($showPiece);
        }
    }

    public function getDescription(): array
    {
        return array_map(
            function (ShowPiece $showPiece) {
                return $showPiece->getDescription();
            },
            $this->showPieces
        );
    }
}