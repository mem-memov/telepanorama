<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use Telepanorama\Partner\Exhibition\Server as Partner;
use Telepanorama\Partner\Exhibition\ServerUnavailable;

class Exhibition
{
    private InventoryRegistry $inventoryRegistry;
    private Partner $partner;

    public function __construct (
        InventoryRegistry $inventoryRegistry,
        Partner $partner
    ) {
        $this->inventoryRegistry = $inventoryRegistry;
        $this->partner = $partner;
    }

    /**
     * @throws ServerUnavailable
     */
    public function createShowcase(): Showcase
    {
        $id = $this->inventoryRegistry->createInventoryNumber();

        $exhibitor = $this->partner->connect();

        $showcase = new Showcase(
            $id,
            new ShowPieceCollection()
        );

        $description = json_encode($showcase->getDescription());
        $localFile = $id . '_showcase.json';

        $exhibitor->createOnLocalServer($description, $localFile);

        return $showcase;
    }

    /**
     * @throws ServerUnavailable
     */
    public function findShowcase(string $id): Showcase
    {
        $exhibitor = $this->partner->connect();

        return new Showcase(
            $id,
            new ShowPieceCollection()
        );
    }
}