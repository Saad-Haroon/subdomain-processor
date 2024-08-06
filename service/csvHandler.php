<?php

/**
 * @param $csvFilePath
 * @return array|void
 */
function readCsvFile($csvFilePath)
{
    if (!file_exists($csvFilePath)) {
        die('CSV file not found.');
    }

    $csvFile = fopen($csvFilePath, 'r');
    if ($csvFile === false) {
        die('Failed to open CSV file.');
    }

    $csvSubdomains = [];
    $isFirstRow = true;
    while (($row = fgetcsv($csvFile)) !== false) {
        if ($isFirstRow) {
            $isFirstRow = false;
            continue;
        }
        $csvSubdomains[] = $row[0];
    }
    fclose($csvFile);

    return $csvSubdomains;
}
