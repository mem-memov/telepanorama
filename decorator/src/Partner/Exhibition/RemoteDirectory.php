<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

use Throwable;

class RemoteDirectory
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
     * @throws RemoteDeleteFailed
     */
    public function delete(RelativePath $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());

        $isDeleted = @ssh2_sftp_unlink($this->sftp, $fullRemotePath);

        if (false === $isDeleted) {
            throw new RemoteDeleteFailed($fullRemotePath);
        }
    }

    /**
     * @throws RemoteSendFailed
     */
    public function send(RelativePath $localPath, RelativePath $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());
        $fullLocalPath = $this->paths->createLocalPath($localPath->getPath());

        $isDirectoryCreated = @ssh2_sftp_mkdir($this->sftp, dirname($fullRemotePath), 0777, true);

        if (false === $isDirectoryCreated) {
            throw new RemoteDirectoryCreateFailed($fullRemotePath);
        }

        $isSent = @ssh2_scp_send($this->ssh, $fullLocalPath, $fullRemotePath, 0644);

        if (false === $isSent) {
            throw new RemoteSendFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }
    }

    /**
     * @throws RemoteReceiveFailed
     */
    public function receive(RelativePath $remotePath, RelativePath $localPath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath->getPath());
        $fullLocalPath = $this->paths->createLocalPath($localPath->getPath());

        $isReceived = @ssh2_scp_recv($this->ssh, $fullRemotePath, $fullLocalPath);

        if (false === $isReceived) {
            throw new RemoteReceiveFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }
    }
}