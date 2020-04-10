<?php

namespace Telepanorama\Showcase;

class Showpiece
{
    private string $file;

    public function __construct(
        string $file
    ) {
        $this->file = $file;
    }
}