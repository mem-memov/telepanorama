<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

class LocalDirectory
{
    /**
     * @throws LocalCreateFailed
     */
    public function createFile(string $content, string $path): void
    {
        $isCreated = @file_put_contents($path, $content);

        if (false === $isCreated) {
            throw new LocalCreateFailed($path);
        }
    }

    public function readFile(string $path): string
    {
        $contents = @file_get_contents($path);

        if (false === $contents) {
            throw new LocalReadFailed($path);
        }

        return $contents;
    }

    /**
     * @throws LocalDeleteFailed
     */
    public function deleteFile(string $path): void
    {
        $isDeleted = @unlink($path);

        if (false === $isDeleted) {
            throw new LocalDeleteFailed($path);
        }
    }
}