<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Smtp;

use Telepanorama\Site\Reporter;

class ReportingConnection
{
    private Connection $connection;
    private Reporter $reporter;

    public function __construct(
        Connection $connection,
        Reporter $reporter
    ) {
        $this->connection = $connection;
        $this->reporter = $reporter;
    }

    /**
     * @throws SendFailed
     */
    public function sendMessage(string $receiverAddress, string $subject, string $message = ''): void
    {
        try {
            $this->connection->sendMessage($receiverAddress, $subject, $message);
        } catch (SendSucceeded $event) {
            $this->reporter->witness($event);
        }
    }
}
