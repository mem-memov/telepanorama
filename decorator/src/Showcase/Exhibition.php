<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use Telepanorama\Partner\Exhibition\RelativePath;
use Telepanorama\Partner\Exhibition\Server as Partner;
use Telepanorama\Partner\Exhibition\ServerUnavailable;
use SplFileInfo;

class Exhibition
{
    private InventoryRegistry $inventoryRegistry;
    private Partner $partner;
    private ShowcaseBuilder $showcaseBuilder;

    public function __construct (
        InventoryRegistry $inventoryRegistry,
        Partner $partner,
        ShowcaseBuilder $showcaseBuilder
    ) {
        $this->inventoryRegistry = $inventoryRegistry;
        $this->partner = $partner;
        $this->showcaseBuilder = $showcaseBuilder;
    }

    /**
     * @throws ServerUnavailable
     */
    public function createShowcase(Showcase $showcase): void
    {
        $this->showcaseBuilder->makeShelves($showcase);
        $this->showcaseBuilder->installGlass($showcase);
    }

    /**
     * @throws ServerUnavailable
     */
    public function findShowcase(string $inventoryNumber): Showcase
    {
        $localFile = new RelativePath($inventoryNumber . '_showcase.json');
        $remoteFile = new RelativePath('case/' . $inventoryNumber . '/showcase.json');

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
            $showcaseData['inventoryNumber'],
            $showpieceCollection
        );
    }

    /**
     * @throws ServerUnavailable
     */
    public function replaceShowcase(Showcase $showcase): void
    {
        $description = json_encode($showcase->getDescription());

        $localFile = new RelativePath($showcase->getInventoryNumber() . '_showcase.json');
        $remoteFile = new RelativePath('case/' . $showcase->getInventoryNumber() . '/showcase.json');

        $exhibitor = $this->partner->connect();
        $exhibitor->createOnLocalServer($description, $localFile);
        $exhibitor->deleteOnRemoteServer($remoteFile);
        $exhibitor->sendToRemoteServer($localFile, $remoteFile);
        $exhibitor->deleteOnLocalServer($localFile);
    }

    public function takeShowpiece(string $inventoryNumber, string $localPanoramaAbsolutePath): Showpiece
    {
        $exhibitor = $this->partner->connect();

        $localInfo = new SplFileInfo($localPanoramaAbsolutePath);
        $localMd5 = md5_file($localPanoramaAbsolutePath);
        $panoramaFile = $localMd5 . '.' . $localInfo->getExtension();

        $localPanorama = new RelativePath($panoramaFile);
        $exhibitor->moveOnLocalServer($localPanoramaAbsolutePath, $localPanorama);

        $remotePanorama = new RelativePath('image/' . $inventoryNumber . '/' . $panoramaFile);;
        $exhibitor->sendToRemoteServer($localPanorama, $remotePanorama);

        return new Showpiece($panoramaFile);
    }
}