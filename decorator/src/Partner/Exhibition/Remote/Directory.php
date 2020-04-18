<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition\Remote;

use Telepanorama\Partner\Exhibition\Paths;
use Telepanorama\Partner\Exhibition\RelativePath;

class Directory
{
    private $ssh;
    private $sftp;
    private Paths $paths;

    public function __construct(
        $ssh,
        $sftp,
        Paths $paths
    ) {
        $this->ssh = $ssh;
        $this->sftp = $sftp;
        $this->paths = $paths;
    }

    /**
     * @throws DeleteFailed
     * @throws DeleteSucceeded
     */
    public function delete(RelativePath $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());

        $isDeleted = @ssh2_sftp_unlink($this->sftp, $fullRemotePath);

        if (false === $isDeleted) {
            throw new DeleteFailed($fullRemotePath);
        }

        throw new DeleteSucceeded($remotePath);
    }

    /**
     * @throws DirectoryCreateFailed
     * @throws SendFailed
     * @throws SendSucceeded
     */
    public function send(RelativePath $localPath, RelativePath $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());
        $fullLocalPath = $this->paths->createLocalPath($localPath->getPath());

        $remoteDirectory = dirname($fullRemotePath);

        $isDirectoryPresent = @ssh2_sftp_stat($this->sftp, $remoteDirectory);

        if (false === $isDirectoryPresent) {
            $isDirectoryCreated = @ssh2_sftp_mkdir($this->sftp, $remoteDirectory, 0777, true);

            if (false === $isDirectoryCreated) {
                throw new DirectoryCreateFailed($remoteDirectory);
            }
        }

        $isSent = @ssh2_scp_send($this->ssh, $fullLocalPath, $fullRemotePath, 0644);

        if (false === $isSent) {
            throw new SendFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }

        throw new SendSucceeded($localPath, $remotePath);
    }

    /**
     * @throws ReceiveFailed
     * @throws ReceiveSucceeded
     */
    public function receive(RelativePath $remotePath, RelativePath $localPath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());
        $fullLocalPath = $this->paths->createLocalPath($localPath->getPath());

        $isReceived = @ssh2_scp_recv($this->ssh, $fullRemotePath, $fullLocalPath);

        if (false === $isReceived) {
            throw new ReceiveFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }

        throw new ReceiveSucceeded($remotePath, $localPath);
    }

    /**
     * @throws CopyFailed
     * @throws CopySucceeded
     */
    public function copy(RelativePath $remoteFilePath, RelativePath $remoteLinkPath): void
    {
        $fullRemoteFilePath = $this->paths->createRemotePath($remoteFilePath->getPath());
        $fullRemoteLinkPath = $this->paths->createLocalPath($remoteLinkPath->getPath());

        $isCopied = ssh2_sftp_symlink($this->sftp, $fullRemoteFilePath, $fullRemoteLinkPath);

        if (false === $isCopied) {
            throw new CopyFailed($fullRemoteFilePath . ' -> ' . $fullRemoteLinkPath);
        }

        throw new CopySucceeded($remoteFilePath, $remoteLinkPath);
    }
}