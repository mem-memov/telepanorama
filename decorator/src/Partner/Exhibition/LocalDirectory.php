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
        $isCreated = file_put_contents($path, $content);

        if (false === $isCreated) {
            throw new LocalCreateFailed($path);
        }
    }

    public function readFile(string $path): string
    {
        return file_get_contents($path);
    }

    /**
     * @throws LocalDeleteFailed
     */
    public function deleteFile(string $path): void
    {
        $isDeleted = unlink($path);

        if (false === $isDeleted) {
            throw new LocalDeleteFailed($path);
        }
    }
}