<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

class Picker
{
    private $postman;

    public function __construct(
        Postman $postman
    ) {
        $this->postman = $postman;
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

        return new Image($path);
    }
}
