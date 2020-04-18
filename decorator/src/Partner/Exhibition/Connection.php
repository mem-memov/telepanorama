<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

use Telepanorama\Partner\Exhibition\Local\ReportingDirectory as LocalDirectory;
use Telepanorama\Partner\Exhibition\Remote\ReportingDirectory as RemoteDirectory;

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

    /**
     * @throws Remote\DeleteFailed
     */
    public function deleteOnRemoteServer(RelativePath $remotePath): void
    {
        $this->remoteDirectory->delete($remotePath);
    }

    /**
     * @throws Remote\DirectoryCreateFailed
     * @throws Remote\SendFailed
     */
    public function sendToRemoteServer(RelativePath $localPath, RelativePath $remotePath): void
    {
        $this->remoteDirectory->send($localPath, $remotePath);
    }

    /**
     * @throws Remote\ReceiveFailed
     */
    public function receiveFromRemoteServer(RelativePath $remotePath, RelativePath $localPath): void
    {
        $this->remoteDirectory->receive($remotePath, $localPath);
    }

    /**
     * @throws Remote\CopyFailed
     */
    public function copyOnRemoteServer(RelativePath $remoteFilePath, RelativePath $remoteLinkPath): void
    {
        $this->remoteDirectory->copy($remoteFilePath, $remoteLinkPath);
    }

    /**
     * @throws Local\CreateFailed
     */
    public function createOnLocalServer(string $content, RelativePath $localPath): void
    {
        $this->localDirectory->createFile($content, $localPath);
    }

    /**
     * @throws Local\ReadFailed
     */
    public function readOnLocalServer(RelativePath $localPath): string
    {
        return $this->localDirectory->readFile($localPath);
    }

    /**
     * @throws Local\MoveFailed
     */
    public function moveOnLocalServer(string $absolutePath, RelativePath $localPath): void
    {
        $this->localDirectory->moveFile($absolutePath, $localPath);
    }

    /**
     * @throws Local\DeleteFailed
     */
    public function deleteOnLocalServer(RelativePath $localPath): void
    {
        $this->localDirectory->deleteFile($localPath);
    }
}