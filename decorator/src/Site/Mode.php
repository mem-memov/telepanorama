<?php

declare(strict_types=1);

namespace Telepanorama\Site;

class Mode
{
    public function isUsedByDeveloper(): bool
    {
        return getenv('SITE_MODE') === 'dev';
    }

    public function isUserOnProductionServer(): bool
    {
        return getenv('SITE_MODE') === 'prod';
    }
}
