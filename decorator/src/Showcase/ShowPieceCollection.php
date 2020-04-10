<?php

namespace Telepanorama\Showcase;

class ShowPieceCollection
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
}