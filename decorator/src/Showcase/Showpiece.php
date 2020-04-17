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

    public function isEqual(self $showpiece): bool
    {
        return $this->file === $showpiece->file;
    }

    public function getDescription(): array
    {
        return [
            'file' => $this->file
        ];
    }

    public function getUrl(string $inventoryNumber): string
    {
        return 'http://telepanorama.org/image/' . $inventoryNumber . '/' . $this->file;
    }
}