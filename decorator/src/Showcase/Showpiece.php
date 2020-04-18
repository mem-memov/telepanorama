<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class Showpiece implements WithDescription
{
    private string $file;

    public function __construct(
        string $file
    ) {
        $this->file = $file;
    }

    public function isEqual(self $another): bool
    {
        return $this->file === $another->file;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getDescription(): array
    {
        return [
            'file' => $this->file
        ];
    }
}