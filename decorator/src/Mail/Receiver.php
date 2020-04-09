<?php

namespace Telepanorama\Mail;

class Receiver
{
    private Picker $picker;

    public function __construct(
        Picker $picker
    ) {
        $this->picker = $picker;
    }

    public function keepImage(): void
    {
        $image = $this->picker->pickImage();

        If (null === $image) {
            return;
        }
    }
}