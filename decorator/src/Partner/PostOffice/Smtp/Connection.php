<?php

declare(strict_types=1);

namespace Telepanorama\Partner\PostOffice\Smtp;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Exception;

class Connection
{
    /**
     * @throws SendFailed
     */
    public function sendMessage(string $receiverAddress, string $subject): void
    {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = 'telepanorama.org@gmail.com';

        //Password to use for SMTP authentication
        $mail->Password = 'No276113@';

        try {
            $mail->setFrom('telepanorama.org@gmail.com', 'Telepanorama Organization');
            $mail->addAddress($receiverAddress);
            $mail->Subject = $subject;
            $isSent = $mail->send();
        } catch (Exception $exception) {
            throw new SendFailed('Mailer Error: '. $exception->getMessage());
        }

        if (false === $isSent) {
            throw new SendFailed('Mailer Error: '. $mail->ErrorInfo);
        }
    }
}