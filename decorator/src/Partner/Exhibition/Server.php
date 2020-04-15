<?php

declare(strict_types=1);

namespace Telepanorama\Partner\Exhibition;

use Telepanorama\Partner\Exhibition\Local\Directory as LocalDirectory;
use Telepanorama\Partner\Exhibition\Remote\Directory as RemoteDirectory;
use Telepanorama\Partner\Exhibition\Remote\ReportingDirectory as RemoteReportingDirectory;
use Telepanorama\Site\Reporter;

class Server
{
    private Reporter $reporter;
    private ?Connection $connection = null;

    public function __construct(
        Reporter $reporter
    ) {
        $this->reporter = $reporter;
    }

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
                new RemoteReportingDirectory(
                    new RemoteDirectory($ssh, $sftp, $paths),
                    $this->reporter
                ),
                new LocalDirectory($paths)
            );
        }

        return $this->connection;
    }
}