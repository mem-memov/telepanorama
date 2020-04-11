<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

interface WithDescription
{
    public function getDescription(): array;
}