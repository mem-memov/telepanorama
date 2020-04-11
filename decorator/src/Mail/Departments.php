<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

interface Departments
{
    public function handlePackage(Package $package): void;
}