<?php

namespace Telepanorama\Mail;

class Image
{
    private string $path;

    public function __construct(
        string $path
    ) {
        $this->path = $path;
    }

    public function getPublished(): void
    {
        $connection = \ssh2_connect('showcase-nginx', 22);

        $ok = \ssh2_auth_password($connection, 'www-data', 'ssh-password');

        \ssh2_scp_send($connection, $this->path, '/var/www/textures/2.jpg', 0644);
    }
}