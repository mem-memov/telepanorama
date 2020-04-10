<?php

namespace Telepanorama\Showcase;

class Inventory
{
    public function createInventoryNumber(): string
    {
        return substr(base64_encode(md5(microtime())), 0, 10);
    }
}