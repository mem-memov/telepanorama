<?php

namespace Telepanorama\Showcase;

class Showcase
{
    private string $id;
    private ShowPieceCollection $showPieceCollection;

    public function __construct(
        string $id
    ) {
        $this->id = $id;
    }
}