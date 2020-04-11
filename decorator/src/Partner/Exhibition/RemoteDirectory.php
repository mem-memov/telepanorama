<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

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

    public function delete(string $remotePath): void
    {
        $isDeleted = ssh2_sftp_unlink($this->sftp, $remotePath);

        if (false === $isDeleted) {

        }
    }

    public function send(string $localPath, string $remotePath): void
    {
        $isSent = ssh2_scp_send($this->ssh, $localPath, $remotePath, 0644);

        if (false === $isSent) {

        }
    }

    public function receive(string $remotePath, string $localPath): void
    {
        $isReceived = ssh2_scp_recv($this->ssh, $remotePath, $localPath);

        if (false === $isReceived) {

        }
    }
}