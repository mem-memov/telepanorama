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
     */
    public function createFile(string $content, RelativePath $path): void
    {
        $fullPath = $this->paths->createLocalPath($path->getPath());

        $isCreated = @file_put_contents($fullPath, $content);

        if (false === $isCreated) {
            throw new CreateFailed($fullPath);
        }
    }

    /**
     * @throws ReadFailed
     */
    public function readFile(RelativePath $path): string
    {
        $fullPath = $this->paths->createLocalPath($path->getPath());

        $contents = @file_get_contents($fullPath);

        if (false === $contents) {
            throw new ReadFailed($fullPath);
        }

        return $contents;
    }

    /**
     * @throws MoveFailed
     */
    public function moveFile(string $absoluteOriginPath, RelativePath $destinationPath): void
    {
        $fullPath = $this->paths->createLocalPath($destinationPath->getPath());

        $isMoved = rename($absoluteOriginPath, $fullPath);

        if (false === $isMoved) {
            throw new MoveFailed($absoluteOriginPath . ' -> ' . $fullPath);
        }
    }

    /**
     * @throws DeleteFailed
     */
    public function deleteFile(RelativePath $path): void
    {
        $fullPath = $this->paths->createLocalPath($path->getPath());

        $isDeleted = @unlink($fullPath);

        if (false === $isDeleted) {
            throw new DeleteFailed($fullPath);
        }
    }
}