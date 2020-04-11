<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

class Connection
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

    public function deleteOnRemoteServer(string $remotePath): void
    {
        ssh2_sftp_unlink($this->sftp, $remotePath);
    }

    public function deleteOnLocalServer(string $localPath): void
    {
        unlink($localPath);
    }

    public function sendToRemoteServer(string $localPath, string $remotePath): void
    {
        ssh2_scp_send($this->ssh, $localPath, $remotePath, 0644);
    }
}