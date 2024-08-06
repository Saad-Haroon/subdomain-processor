<?php

use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;

require 'vendor/autoload.php';

/**
 * @param $apiKey
 * @param $toEmails
 * @param $subject
 * @param $content
 * @return void
 * @throws TypeException
 */
function sendEmail($apiKey, $toEmails, $subject, $content)
{
    if (!empty($toEmails)) {
        $email = new Mail();
        $email->setFrom("staging@fxmedsupport.com", "FxMedSupport Staging");
        foreach ($toEmails as $toEmail) {
            $email->addTo($toEmail);
        }
        $email->setSubject($subject);
        $email->addContent("text/plain", strip_tags(preg_replace('/<br\s?\/?>/ius', "\n", $content)));
        $email->addContent("text/html", $content);

        $sendgrid = new \SendGrid($apiKey);
        try {
            $response = $sendgrid->send($email);
            if ($response->statusCode() >= 400) {
                die('Failed to send email.');
            }
        } catch (Exception $e) {
            die('Caught exception: ' . $e->getMessage());
        }
    }
}
