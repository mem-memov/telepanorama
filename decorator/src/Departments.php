<?php

declare(strict_types=1);

namespace Telepanorama;

use Telepanorama\Mail\Departments as MailDepartments;
use Telepanorama\Mail\Package;
use Telepanorama\Mail\Postman;
use Telepanorama\Showcase\Decorator;
use Telepanorama\ArtStudio\Artist;
use Telepanorama\Order\Accountant;
use Telepanorama\Partner\Exhibition\ServerUnavailable;

class Departments implements MailDepartments
{
    private Decorator $decorator;
    private Accountant $accountant;
    private Postman $postman;
    private Artist $artist;

    public function __construct(
        Decorator $decorator,
        Accountant $accountant,
        Postman $postman,
        Artist $artist
    ) {
        $this->decorator = $decorator;
        $this->accountant = $accountant;
        $this->postman = $postman;
        $this->artist = $artist;
    }

    /**
     * @throws ServerUnavailable
     */
    public function handlePackage(Package $package): void
    {
        if ($package->hasSubjectCaseInsensitive('order')) {
            $inventoryNumber = $this->accountant->provideNextInventoryNumber();
            $this->decorator->setUpEmptyShowcase($inventoryNumber);
            $this->postman->sendReplyToPackage($package, 'order(' . $inventoryNumber . ')', 'New order has been created');
            return;
        }

        $pattern = '/^.*order\((.+)\).*$/';
        $countOrders = preg_match($pattern, $package->getSubject());
        if (1 !== $countOrders) {
            return;
        }
        $inventoryNumber = preg_replace($pattern, '$1', $package->getSubject());
        if ($package->hasAttachment() && $this->decorator->recallShowcase($inventoryNumber)) {
            $album = $this->artist->paint($package->getAttachmentPath());
            $showcase = $this->decorator->addShowpieceToShowcase($inventoryNumber, $package->getAttachmentPath());
            $this->postman->sendReplyToPackage($package, 'image', 'http://telepanorama.org/case/' . $inventoryNumber . '/#' . $showcase->getFile());
            return;
        }
    }
}