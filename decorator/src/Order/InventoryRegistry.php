<?php

declare(strict_types=1);

namespace Telepanorama\Order;

class InventoryRegistry
{
    public function createInventoryNumber(): string
    {
        return substr(base64_encode(md5(microtime())), 0, 10);
    }
}