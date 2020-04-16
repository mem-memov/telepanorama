<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Local;

use Telepanorama\Partner\Exhibition\RelativePath;
use Telepanorama\Site\Reporter;

class ReportingDirectory
{
    private Directory $directory;
    private Reporter $reporter;

    public function __construct(
        Directory $directory,
        Reporter $reporter
    ) {
        $this->directory = $directory;
        $this->reporter = $reporter;
    }

    /**
     * @throws CreateFailed
     */
    public function createFile(string $content, RelativePath $path): void
    {
        try {
            $this->directory->createFile($content, $path);
        } catch (CreateSucceeded $event) {
            $this->reporter->witness($event);
        }
    }

    /**
     * @throws ReadFailed
     */
    public function readFile(RelativePath $path): string
    {
        try {
            $this->directory->readFile($path);
        } catch (ReadSucceeded $event) {
            $this->reporter->witness($event);
            return $event->getContents();
        }
    }

    /**
     * @throws MoveFailed
     */
    public function moveFile(string $absoluteOriginPath, RelativePath $destinationPath): void
    {
        try {
            $this->directory->moveFile($absoluteOriginPath, $destinationPath);
        } catch (MoveSucceeded $event) {
            $this->reporter->witness($event);
        }
    }

    /**
     * @throws DeleteFailed
     */
    public function deleteFile(RelativePath $path): void
    {
        try {
            $this->directory->deleteFile($path);;
        } catch (DeleteSucceeded $event) {
            $this->reporter->witness($event);
        }
    }
}
