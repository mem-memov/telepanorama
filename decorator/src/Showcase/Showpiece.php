<?php

declare(strict_types=1);

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