<?php

namespace Telepanorama\Showcase;

class Exhibition
{
    public function createShowcase(): Showcase
    {
        $id = '';

        return new Showcase($id);
    }

    public function findShowcase(string $id): Showcase
    {
        return new Showcase($id);
    }
}