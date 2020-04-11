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
}