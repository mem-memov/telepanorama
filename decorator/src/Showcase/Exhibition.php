<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use Telepanorama\ArtStudio\Album;
use Telepanorama\ArtStudio\ImageInAlbum;
use Telepanorama\Partner\Exhibition\RelativePath;
use Telepanorama\Partner\Exhibition\Server as Partner;

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
     * @throws \Telepanorama\Partner\Exhibition\ServerUnavailable
     */
    public function createShowcase(Showcase $showcase): void
    {
        $this->showcaseBuilder->makeShelves($showcase);
        $this->showcaseBuilder->installGlass($showcase);
    }

    /**
     * @throws \Telepanorama\Partner\Exhibition\ServerUnavailable
     * @throws \Telepanorama\Partner\Exhibition\Local\DeleteFailed
     * @throws \Telepanorama\Partner\Exhibition\Local\ReadFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\ReceiveFailed
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
     * @throws \Telepanorama\Partner\Exhibition\ServerUnavailable
     * @throws \Telepanorama\Partner\Exhibition\Local\CreateFailed
     * @throws \Telepanorama\Partner\Exhibition\Local\DeleteFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\DeleteFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\DirectoryCreateFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\SendFailed
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

    /**
     * @throws \Telepanorama\Partner\Exhibition\Local\MoveFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\DirectoryCreateFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\SendFailed
     * @throws \Telepanorama\Partner\Exhibition\ServerUnavailable
     */
    /**
     * @throws \Telepanorama\Partner\Exhibition\Local\DeleteFailed
     * @throws \Telepanorama\Partner\Exhibition\Local\MoveFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\DirectoryCreateFailed
     * @throws \Telepanorama\Partner\Exhibition\Remote\SendFailed
     * @throws \Telepanorama\Partner\Exhibition\ServerUnavailable
     */
    public function takeShowpiece(Panorama $panorama, Album $album): Showpiece
    {
        $showpiece = new Showpiece($panorama->nameFile());

        $exhibitor = $this->partner->connect();

        $localPanorama = new RelativePath($showpiece->getFile());
        $exhibitor->moveOnLocalServer($panorama->getAbsolutePath(), $localPanorama);

        $remotePanorama = new RelativePath('image/' . $showpiece->getFile());;
        $exhibitor->sendToRemoteServer($localPanorama, $remotePanorama);

        $exhibitor->deleteOnLocalServer($localPanorama);

        $album->eachImage(function (ImageInAlbum $imageInAlbum) use ($exhibitor) {
            $localImage = new RelativePath($imageInAlbum->getFile());
            $exhibitor->moveOnLocalServer($imageInAlbum->getAbsolutePath(), $localImage);

            $remoteImage = new RelativePath('image/' . $imageInAlbum->getFile());;
            $exhibitor->sendToRemoteServer($localImage, $remoteImage);

            $exhibitor->deleteOnLocalServer($localImage);
        });

        return $showpiece;
    }
}