<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

use SplFileInfo;

class Panorama
{
    private string $absolutePath;

    public function __construct(
        string $absolutePath
    ) {
        $this->absolutePath = $absolutePath;
    }

    public function getAbsolutePath(): string
    {
        return $this->absolutePath;
    }

    public function nameFile(): string
    {
        $fileInfo = new SplFileInfo($this->absolutePath);

        return md5_file($this->absolutePath) . '.' . strtolower($fileInfo->getExtension());
    }
}
