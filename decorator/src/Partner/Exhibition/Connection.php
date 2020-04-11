<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

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

    public function deleteOnRemoteServer(string $remotePath): void
    {
        $this->remoteDirectory->delete($remotePath);
    }

    public function sendToRemoteServer(string $localPath, string $remotePath): void
    {
        $this->remoteDirectory->send($localPath, $remotePath);
    }

    public function receiveFromRemoteServer(string $remotePath, string $localPath): void
    {
        $this->remoteDirectory->receive($remotePath, $localPath);
    }

    public function createOnLocalServer(string $content, string $localPath): void
    {
        $this->localDirectory->createFile($localPath, $content);
    }

    public function readOnLocalServer(string $localPath): void
    {
        $this->localDirectory->readFile($localPath);
    }

    public function deleteOnLocalServer(string $localPath): void
    {
        $this->localDirectory->deleteFile($localPath);
    }
}