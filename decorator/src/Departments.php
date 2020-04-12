<?php

declare(strict_types=1);

namespace Telepanorama;

use Telepanorama\Mail\Departments as MailDepartments;
use Telepanorama\Mail\Package;
use Telepanorama\Mail\Postman;
use Telepanorama\Showcase\Decorator;
use Telepanorama\Order\Accountant;

class Departments implements MailDepartments
{
    private Decorator $decorator;
    private Accountant $accountant;
    private Postman $postman;

    public function __construct(
        Decorator $decorator,
        Accountant $accountant,
        Postman $postman
    ) {
        $this->decorator = $decorator;
        $this->accountant = $accountant;
        $this->postman = $postman;
    }

    public function handlePackage(Package $package): void
    {
        if ($package->hasSubject('order')) {
            $inventoryNumber = $this->accountant->provideNextInventoryNumber();
            $this->decorator->setUpEmptyShowcase($inventoryNumber);
            $this->postman->sendReplyToPackage($package, $inventoryNumber);
            return;
        }

        $inventoryNumber = $package->getSubject();
        if ($package->hasAttachment() && $this->decorator->recallShowcase($inventoryNumber)) {
            $this->decorator->addShowpieceToShowcase($inventoryNumber, $package->getAttachmentPath());
            return;
        }
    }
}