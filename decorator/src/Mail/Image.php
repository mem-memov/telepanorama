<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

class Image
{
    private ?string $path;

    public function __construct(
        string $path
    ) {
        $this->path = $path;
    }

    public function getPublished(): void
    {
        if (null !== $this->path) {
            $connection = ssh2_connect('showcase-nginx', 22);
            $ok = ssh2_auth_password($connection, 'www-data', 'ssh-password');

            $sftp = ssh2_sftp($connection);
            ssh2_sftp_unlink($sftp, '/var/www/textures/2.jpg');

            ssh2_scp_send($connection, $this->path, '/var/www/textures/2.jpg', 0644);

            unlink($this->path);

            $this->path = null;
        }
    }
}