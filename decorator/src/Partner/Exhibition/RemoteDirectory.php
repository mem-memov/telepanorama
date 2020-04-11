<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

use Throwable;

class RemoteDirectory
{
    private $ssh;
    private $sftp;

    public function __construct(
        $ssh,
        $sftp
    ) {
        $this->ssh = $ssh;
        $this->sftp = $sftp;
    }

    /**
     * @throws RemoteDeleteFailed
     */
    public function delete(string $remotePath): void
    {
        $isDeleted = @ssh2_sftp_unlink($this->sftp, $remotePath);

        if (false === $isDeleted) {
            throw new RemoteDeleteFailed($remotePath);
        }
    }

    /**
     * @throws RemoteSendFailed
     */
    public function send(string $localPath, string $remotePath): void
    {
        $isSent = @ssh2_scp_send($this->ssh, $localPath, $remotePath, 0644);

        if (false === $isSent) {
            throw new RemoteSendFailed($localPath . ' -> ' . $remotePath);
        }
    }

    /**
     * @throws RemoteReceiveFailed
     */
    public function receive(string $remotePath, string $localPath): void
    {
        $isReceived = @ssh2_scp_recv($this->ssh, $remotePath, $localPath);

        if (false === $isReceived) {
            throw new RemoteReceiveFailed($localPath . ' -> ' . $remotePath);
        }
    }
}