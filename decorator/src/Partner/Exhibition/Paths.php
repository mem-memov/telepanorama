<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

class Paths
{
    private string $baseLocalPath;
    private string $baseRemotePath;

    public function __construct(
        string $baseLocalPath,
        string $baseRemotePath
    ) {
        $this->baseLocalPath = $baseLocalPath;
        $this->baseRemotePath = $baseRemotePath;
    }

    public function createLocalPath(string $relativePath): string
    {
        return $this->baseLocalPath . '/' . $relativePath;
    }

    public function createRemotePath(string $relativePath): string
    {
        return $this->baseRemotePath . '/' . $relativePath;
    }
}