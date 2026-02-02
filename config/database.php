<?php
/**
 * Configuration base de données
 * Médiathèque - BTS SIO SLAM
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'mediatheque');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
]);