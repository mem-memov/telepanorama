<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

use Telepanorama\Partner\Exhibition\RelativePath;
use Telepanorama\Partner\Exhibition\Server as Partner;
use Telepanorama\Partner\Exhibition\ServerUnavailable;

class ShowcaseBuilder
{
    private Partner $partner;

    public function __construct (
        Partner $partner
    ) {
        $this->partner = $partner;
    }

    public function makeShelves(Showcase $showcase): void
    {
        $description = json_encode($showcase->getDescription());

        $localFile = new RelativePath($showcase->getInventoryNumber() . '_showcase.json');
        $remoteFile = new RelativePath('case/' . $showcase->getInventoryNumber() . '/showcase.json');

        $exhibitor = $this->partner->connect();
        $exhibitor->createOnLocalServer($description, $localFile);
        $exhibitor->sendToRemoteServer($localFile, $remoteFile);
        $exhibitor->deleteOnLocalServer($localFile);
    }

    /**
     * @throws ServerUnavailable
     */
    public function installGlass(Showcase $showcase): void
    {
        $remoteFile = new RelativePath('html/index.html');
        $remoteLink = new RelativePath('case/' . $showcase->getInventoryNumber() . '/index.html');

        $exhibitor = $this->partner->connect();
        $exhibitor->copyOnRemoteServer($remoteFile, $remoteLink);
    }
}