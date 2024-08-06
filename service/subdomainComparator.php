<?php

use SendGrid\Mail\TypeException;

/**
 * @param $pdo
 * @param $csvSubdomains
 * @param $csvFilePath
 * @param $sendgridApiKey
 * @param $toEmails
 * @return void
 * @throws TypeException
 */
function compareSubdomains($pdo, $csvSubdomains, $csvFilePath, $sendgridApiKey, $toEmails)
{
    $stmt = $pdo->query("SELECT * FROM subdomains LIMIT 1");
    $dbRow = $stmt->fetch();

    if ($dbRow) {
        $dbSubdomains = json_decode($dbRow['subdomains'], true);
        $dbLastModified = new DateTime($dbRow['updated_at']);

        $csvLastModified = new DateTime('@' . filemtime($csvFilePath));

        if ($csvLastModified > $dbLastModified) {
            $newSubdomains = array_diff($csvSubdomains, $dbSubdomains);

            if (!empty($newSubdomains)) {
                $subject = 'New Subdomains Added';
                $content = "Following new subdomains have been introduced:<br><br>";

                foreach ($newSubdomains as $subdomain) {
                    $content .= '<span style="font-weight: bold">' . $subdomain . "</span><br>";
                }

                sendEmail($sendgridApiKey, $toEmails, $subject, $content);

                $updatedSubdomains = array_unique(array_merge($dbSubdomains, $csvSubdomains));
                $stmt = $pdo->prepare("UPDATE subdomains SET subdomains = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([json_encode($updatedSubdomains), $dbRow['id']]);

                echo 'Subdomains updated and email sent.';
            } else {
                echo 'No new subdomains found.';
            }
        } else {
            echo 'CSV file has not been modified since the last update.';
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO subdomains (subdomains, created_at, updated_at) VALUES (?, NOW(), NOW())");
        $stmt->execute([json_encode($csvSubdomains)]);

        echo 'Subdomains table initialized.';
    }
}
