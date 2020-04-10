<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use PHPUnit\Framework\TestCase;

class InventoryTest extends TestCase
{
    public function testItCreatesInventoryNumber(): void
    {
        $inventoryRegistry = new InventoryRegistry();

        $result = $inventoryRegistry->createInventoryNumber();

        $this->assertEquals(10, strlen($result));
    }
}