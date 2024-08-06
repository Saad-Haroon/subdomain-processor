<?php

$config = require 'config/config.php';
require 'config/database.php';
require 'service/csvHandler.php';
require 'service/emailSender.php';
require 'service/subdomainComparator.php';

$pdo = getDatabaseConnection($config);
$csvSubdomains = readCsvFile($config['csv_file_path']);
compareSubdomains($pdo, $csvSubdomains, $config['csv_file_path'], $config['sendgrid_api_key'], $config['to_emails']);
