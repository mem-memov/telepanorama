<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

use Telepanorama\Partner\Exhibition\Local\Directory as LocalDirectory;
use Telepanorama\Partner\Exhibition\Remote\Directory as RemoteDirectory;

class Connection
{
    private RemoteDirectory $remoteDirectory;
    private LocalDirectory $localDirectory;

    public function __construct(
        RemoteDirectory $remoteDirectory,
        LocalDirectory $localDirectory
    ) {
        $this->remoteDirectory = $remoteDirectory;
        $this->localDirectory = $localDirectory;
    }

    public function deleteOnRemoteServer(RelativePath $remotePath): void
    {
        $this->remoteDirectory->delete($remotePath);
    }

    public function sendToRemoteServer(RelativePath $localPath, RelativePath $remotePath): void
    {
        $this->remoteDirectory->send($localPath, $remotePath);
    }

    public function receiveFromRemoteServer(RelativePath $remotePath, RelativePath $localPath): void
    {
        $this->remoteDirectory->receive($remotePath, $localPath);
    }

    public function createOnLocalServer(string $content, RelativePath $localPath): void
    {
        $this->localDirectory->createFile($content, $localPath);
    }

    public function readOnLocalServer(RelativePath $localPath): string
    {
        return $this->localDirectory->readFile($localPath);
    }

    public function deleteOnLocalServer(RelativePath $localPath): void
    {
        $this->localDirectory->deleteFile($localPath);
    }
}