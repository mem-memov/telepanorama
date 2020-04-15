<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Remote;

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
     * @throws DeleteFailed
     */
    public function delete(RelativePath $remotePath): void
    {
        try {
            $this->directory->delete($remotePath);
        } catch (DeleteSucceeded $event) {
            $this->reporter->witness($event);
        }
    }

    /**
     * @throws DirectoryCreateFailed
     * @throws SendFailed
     */
    public function send(RelativePath $localPath, RelativePath $remotePath): void
    {
        try {
            $this->directory->send($localPath, $remotePath);
        } catch (SendSucceeded $event) {
            $this->reporter->witness($event);
        }
    }

    /**
     * @throws ReceiveFailed
     */
    public function receive(RelativePath $remotePath, RelativePath $localPath): void
    {
        try {
            $this->directory->receive($remotePath, $localPath);
        } catch (ReceiveSucceeded $event) {
            $this->reporter->witness($event);
        }
    }
}
