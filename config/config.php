<?php
/**
 * Configuration générale de l'application
 * Médiathèque - BTS SIO SLAM
 */

// Mode debug
define('DEBUG', true);

// Informations application
define('APP_NAME', 'Médiathèque');
define('APP_VERSION', '1.0.0');

// URLs
define('BASE_URL', 'http://localhost/mediatheque/public/');

// Chemins
define('ROOT_PATH', dirname(__DIR__) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('VIEWS_PATH', APP_PATH . 'views/');
define('CONTROLLERS_PATH', APP_PATH . 'controllers/');
define('MODELS_PATH', APP_PATH . 'models/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('CONFIG_PATH', ROOT_PATH . 'config/');

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Gestion des erreurs
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}