<?php

namespace Telepanorama\Order;

class Accountant
{
    private InventoryRegistry $inventoryRegistry;

    public function __construct(
        InventoryRegistry $inventoryRegistry
    ) {
        $this->inventoryRegistry = $inventoryRegistry;
    }

    public function provideNextInventoryNumber(): string
    {
        return $this->inventoryRegistry->createInventoryNumber();
    }
}