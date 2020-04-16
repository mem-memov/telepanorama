<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Local;

use Telepanorama\Partner\Exhibition\Paths;
use Telepanorama\Partner\Exhibition\RelativePath;

class Directory
{
    private Paths $paths;

    public function __construct(
        Paths $paths
    ) {
        $this->paths = $paths;
    }

    /**
     * @throws CreateFailed
     * @throws CreateSucceeded
     */
    public function createFile(string $content, RelativePath $path): void
    {
        $fullPath = $this->paths->createLocalPath($path->getPath());

        $isCreated = @file_put_contents($fullPath, $content);

        if (false === $isCreated) {
            throw new CreateFailed($fullPath);
        }

        throw new CreateSucceeded($content, $path);
    }

    /**
     * @throws ReadFailed
     * @throws ReadSucceeded
     */
    public function readFile(RelativePath $path): void
    {
        $fullPath = $this->paths->createLocalPath($path->getPath());

        $contents = @file_get_contents($fullPath);

        if (false === $contents) {
            throw new ReadFailed($fullPath);
        }

        throw new ReadSucceeded($path, $contents);
    }

    /**
     * @throws MoveFailed
     * @throws MoveSucceeded
     */
    public function moveFile(string $absoluteOriginPath, RelativePath $destinationPath): void
    {
        $fullPath = $this->paths->createLocalPath($destinationPath->getPath());

        $isMoved = rename($absoluteOriginPath, $fullPath);

        if (false === $isMoved) {
            throw new MoveFailed($absoluteOriginPath . ' -> ' . $fullPath);
        }

        throw new MoveSucceeded($absoluteOriginPath, $destinationPath);
    }

    /**
     * @throws DeleteFailed
     * @throws DeleteSucceeded
     */
    public function deleteFile(RelativePath $path): void
    {
        $fullPath = $this->paths->createLocalPath($path->getPath());

        $isDeleted = @unlink($fullPath);

        if (false === $isDeleted) {
            throw new DeleteFailed($fullPath);
        }

        throw new DeleteSucceeded($path);
    }
}