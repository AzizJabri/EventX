<?php
define('DB_HOST', $_ENV['AZURE_MYSQL_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['AZURE_MYSQL_DBNAME'] ?? 'event_db');
define('DB_USER', $_ENV['AZURE_MYSQL_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['AZURE_MYSQL_PASSWORD'] ?? '');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', 'http://localhost/php/venue-booking');
?>