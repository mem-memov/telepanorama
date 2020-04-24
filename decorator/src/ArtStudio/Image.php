<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Image
{
    private string $inventoryNumber;
    private string $extension;
    private Rectangle $resolution;
    private int $size;
    private string $mimeType;

    public function __construct(
        string $inventoryNumber,
        string $extension,
        Rectangle $resolution,
        int $size,
        string $mimeType
    ) {
        $this->inventoryNumber = $inventoryNumber;
        $this->extension = $extension;
        $this->resolution = $resolution;
        $this->size = $size;
        $this->mimeType = $mimeType;
    }

    public function getFileName(): string
    {
        return $this->inventoryNumber . '.' . $this->extension;
    }

    public function getDescription(): array
    {
        return [
            'file' => $this->getFileName(),
            'width' => $this->resolution->getWidth()->getPixels(),
            'height' => $this->resolution->getHeight()->getPixels(),
            'size' => $this->size,
            'mimeType' => $this->mimeType
        ];
    }
}