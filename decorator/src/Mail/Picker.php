<?php

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
            return null;
        }

        return new Image();
    }
}
