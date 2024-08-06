# subdomain-processor

## Update Config.php - consider as env file

- Add correct values for DB `db`
- Add Sendgrid API Key `sendgrid_api_key`
- Update `to_emails` (command separated)
- Make sure to replace CSV on root and compare name in `csv_file_path`

## Update emailSender.php

- Update `setFrom` email and name - **line:20**

## Start Subdomain Processor
1. Make sure to be at root directory
2. Run following command:
```
composer install
php main.php
```