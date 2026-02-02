<?php
/**
 * Model TypeDocument - Gestion des types de documents
 */

require_once MODELS_PATH . 'Model.php';

class TypeDocument extends Model
{
    protected string $table = 'type_document';
    protected string $primaryKey = 'id_type_document';

    /**
     * Récupérer tous les types triés par libellé
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY libelle_type";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Trouver par libellé
     */
    public function findByLibelle(string $libelle): ?array
    {
        return $this->findBy('libelle_type', $libelle);
    }

    /**
     * Vérifier si le type est utilisé par des documents
     */
    public function isUsed(int $id): bool
    {
        $sql = "SELECT COUNT(*) as total FROM document WHERE id_type_document = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}