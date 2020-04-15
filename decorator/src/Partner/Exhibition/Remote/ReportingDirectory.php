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
        } catch (DeleteSucceeded $deleteSucceeded) {
            $this->reporter->witness($deleteSucceeded);
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
        } catch (SendSucceeded $sendSucceeded) {
            $this->reporter->witness($sendSucceeded);
        }
    }

    /**
     * @throws ReceiveFailed
     */
    public function receive(RelativePath $remotePath, RelativePath $localPath): void
    {
        try {
            $this->directory->receive($remotePath, $localPath);
        } catch (ReceiveSucceeded $receiveSucceeded) {
            $this->reporter->witness($receiveSucceeded);
        }
    }
}
