<?php

namespace Telepanorama\Showcase;

class Exhibition
{
    private InventoryRegistry $inventoryRegistry;

    public function __construct (
        InventoryRegistry $inventoryRegistry
    ) {
        $this->inventoryRegistry = $inventoryRegistry;
    }

    public function createShowcase(): Showcase
    {
        $id = $this->inventoryRegistry->createInventoryNumber();

        return new Showcase($id);
    }

    public function findShowcase(string $id): Showcase
    {
        return new Showcase($id);
    }
}