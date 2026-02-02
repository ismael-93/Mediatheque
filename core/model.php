<?php
/**
 * core/Model.php
 * Classe parente pour tous les modèles
 */
abstract class Model
{
    protected $db;   // L'objet connexion PDO
    protected $table; // Le nom de la table (ex: 'utilisateur')

    public function __construct()
    {
        // On récupère la connexion unique ici !
        $this->db = Database::getInstance();
    }

    // Exemple de méthode générique utile
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }
}