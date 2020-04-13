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
     */
    public function delete(RelativePath $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());

        $isDeleted = @ssh2_sftp_unlink($this->sftp, $fullRemotePath);

        if (false === $isDeleted) {
            throw new DeleteFailed($fullRemotePath);
        }
    }

    /**
     * @throws SendFailed
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
    }

    /**
     * @throws ReceiveFailed
     */
    public function receive(RelativePath $remotePath, RelativePath $localPath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());
        $fullLocalPath = $this->paths->createLocalPath($localPath->getPath());

        $isReceived = @ssh2_scp_recv($this->ssh, $fullRemotePath, $fullLocalPath);

        if (false === $isReceived) {
            throw new ReceiveFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }
    }
}