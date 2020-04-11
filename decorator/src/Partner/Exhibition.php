<?php

declare(strict_types=1);

namespace Telepanorama\Partner;

class Exhibition
{
    public function provideStand()
    {
        $connection = ssh2_connect('showcase-nginx', 22);
        $ok = ssh2_auth_password($connection, 'www-data', 'ssh-password');

        $sftp = ssh2_sftp($connection);
    }
}