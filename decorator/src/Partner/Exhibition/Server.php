<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

class Server
{
    public function connect(): Connection
    {
        $ssh = ssh2_connect('showcase-nginx', 22);
        $ok = ssh2_auth_password($ssh, 'www-data', 'ssh-password');

        $sftp = ssh2_sftp($ssh);

        return new Connection($ssh, $sftp);
    }
}