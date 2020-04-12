<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

class LocalDirectory
{
    private Paths $paths;

    public function __construct(
        Paths $paths
    ) {
        $this->paths = $paths;
    }

    /**
     * @throws LocalCreateFailed
     */
    public function createFile(string $content, string $path): void
    {
        $fullPath = $this->paths->createLocalPath($path);

        $isCreated = @file_put_contents($fullPath, $content);

        if (false === $isCreated) {
            throw new LocalCreateFailed($fullPath);
        }
    }

    public function readFile(string $path): string
    {
        $fullPath = $this->paths->createLocalPath($path);

        $contents = @file_get_contents($fullPath);

        if (false === $contents) {
            throw new LocalReadFailed($fullPath);
        }

        return $contents;
    }

    /**
     * @throws LocalDeleteFailed
     */
    public function deleteFile(string $path): void
    {
        $fullPath = $this->paths->createLocalPath($path);

        $isDeleted = @unlink($fullPath);

        if (false === $isDeleted) {
            throw new LocalDeleteFailed($fullPath);
        }
    }
}