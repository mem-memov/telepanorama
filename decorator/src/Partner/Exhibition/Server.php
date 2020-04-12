<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

use Telepanorama\Partner\Exhibition\Local\Directory as LocalDirectory;
use Telepanorama\Partner\Exhibition\Remote\Directory as RemoteDirectory;

class Server
{
    private ?Connection $connection = null;

    /**
     * @throws ServerUnavailable
     */
    public function connect(): Connection
    {
        if (null === $this->connection) {
            $ssh = ssh2_connect('showcase-nginx', 22);
            $isConnected = ssh2_auth_password($ssh, 'www-data', 'ssh-password');

            if (!$isConnected) {
                throw new ServerUnavailable('SSH connection failed');
            }

            $sftp = ssh2_sftp($ssh);

            $paths = new Paths('/tmp', '/var/www');
            $this->connection = new Connection(
                new RemoteDirectory($ssh, $sftp, $paths),
                new LocalDirectory($paths)
            );
        }

        return $this->connection;
    }
}