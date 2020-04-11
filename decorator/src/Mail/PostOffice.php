<?php

declare(strict_types=1);

namespace Telepanorama\Mail;

use PhpImap\Exceptions\ConnectionException;
use Telepanorama\Partner\PostOffice as PostOfficePartner;
use PhpImap\Mailbox;

class PostOffice
{
    private PostOfficePartner $partner;

    public function __construct(
        PostOfficePartner $partner
    ) {
        $this->partner = $partner;
    }

    /**
     * @return array|int[]
     */
    public function searchMailbox(): array
    {
        $mailbox = $this->partner->openMailBox();

        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            $mailsIds = $mailbox->searchMailbox('ALL');
        } catch(ConnectionException $exception) {
            throw new PostOfficeClosed('IMAP connection failed', 0 , $exception);
        }

        return $mailsIds;
    }

    public function handOutPackage($mailId): Package
    {
        $mailbox = $this->partner->openMailBox();

        $mail = $mailbox->getMail($mailId);

        return new Package($mailId, $mail);
    }

    public function destroyPackage(Package $package): void
    {
        $mailbox = $this->partner->openMailBox();

        $mailbox->deleteMail($package->getMailId());
        $mailbox->expungeDeletedMails();
    }
}
