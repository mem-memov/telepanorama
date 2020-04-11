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
            new ShowpieceCollection()
        );

        $description = json_encode($showcase->getDescription());

        $localFile = $id . '_showcase.json';
        $remoteFile = 'case/' . $id . '/showcase.json';

        $exhibitor = $this->partner->connect();
        $exhibitor->createOnLocalServer($description, $localFile);
        $exhibitor->sendToRemoteServer($localFile, $remoteFile);
        $exhibitor->deleteOnLocalServer($localFile);

        return $showcase;
    }

    /**
     * @throws ServerUnavailable
     */
    public function findShowcase(string $id): Showcase
    {
        $localFile = $id . '_showcase.json';
        $remoteFile = 'case/' . $id . '/showcase.json';

        $exhibitor = $this->partner->connect();
        $exhibitor->receiveFromRemoteServer($remoteFile, $localFile);
        $showcaseJson = $exhibitor->readOnLocalServer($localFile);
        $exhibitor->deleteOnLocalServer($localFile);

        $showcaseData = json_decode($showcaseJson, true);

        $showpieceCollection = new ShowpieceCollection();
        foreach ($showcaseData['showpieces'] as $showpiece) {
            $showpiece = new Showpiece($showpiece['file']);
            $showpieceCollection->add($showpiece);
        }

        return new Showcase(
            $showcaseData['id'],
            $showpieceCollection
        );
    }
}