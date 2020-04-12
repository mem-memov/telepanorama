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
    public function delete(string $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath);

        $isDeleted = @ssh2_sftp_unlink($this->sftp, $fullRemotePath);

        if (false === $isDeleted) {
            throw new RemoteDeleteFailed($fullRemotePath);
        }
    }

    /**
     * @throws RemoteSendFailed
     */
    public function send(string $localPath, string $remotePath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath);
        $fullLocalPath = $this->paths->createLocalPath($localPath);

        $isSent = @ssh2_scp_send($this->ssh, $fullLocalPath, $fullRemotePath, 0644);

        if (false === $isSent) {
            throw new RemoteSendFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }
    }

    /**
     * @throws RemoteReceiveFailed
     */
    public function receive(string $remotePath, string $localPath): void
    {
        $fullRemotePath = $this->paths->createRemotePath($remotePath);
        $fullLocalPath = $this->paths->createLocalPath($localPath);

        $isReceived = @ssh2_scp_recv($this->ssh, $fullRemotePath, $fullLocalPath);

        if (false === $isReceived) {
            throw new RemoteReceiveFailed($fullLocalPath . ' -> ' . $fullRemotePath);
        }
    }
}