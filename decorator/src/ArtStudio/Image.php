<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Image
{
    private string $inventoryNumber;
    private string $extension;
    private Rectangle $imageSize;
    private int $size;
    private int $mimeType;
}