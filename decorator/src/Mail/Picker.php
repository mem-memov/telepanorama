<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

use Telepanorama\Partner\Exhibition\Server as Exhibition;

class Picker
{
    private Postman $postman;
    private Exhibition $exhibition;

    public function __construct(
        Postman $postman,
        Exhibition $exhibition
    ) {
        $this->postman = $postman;
        $this->exhibition = $exhibition;
    }

    public function assignPackage(Departments $departments): void
    {
        $package = $this->postman->bringNextPackage();

        if (null === $package) {
            return;
        }

        $departments->handlePackage($package);

        $this->postman->throwAwayPackage($package);
    }

    public function pickImage(): ?Image
    {
        $package = $this->postman->bringNextPackage();

        if (null === $package) {
            return null;
        }

        if (!$package->hasSubject('foto') || !$package->hasAttachment()) {
            $this->postman->throwAwayPackage($package);
            return null;
        }

        $path = $package->getAttachmentPath();

        $this->postman->throwAwayPackage($package);

        return new Image(
            $path,
            $this->exhibition->connect()
        );
    }
}
