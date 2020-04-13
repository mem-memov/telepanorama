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
            $this->decorator->addShowpieceToShowcase($inventoryNumber, $package->getAttachmentPath());
            $this->postman->sendReplyToPackage($package, 'image', 'http://telepanorama.org/image/' . $inventoryNumber . '/' . basename($package->getAttachmentPath()));
            return;
        }
    }
}