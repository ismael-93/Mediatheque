<?php
/**
 * Classe Database - Singleton PDO
 */

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
            } catch (PDOException $e) {
                die(DEBUG ? "Erreur : " . $e->getMessage() : "Erreur de connexion.");
            }
        }
        return self::$instance;
    }
}