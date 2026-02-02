<?php
/**
 * Model Document - Gestion des documents
 */

require_once MODELS_PATH . 'Model.php';

class Document extends Model
{
    protected string $table = 'document';
    protected string $primaryKey = 'id_document';

    /**
     * Récupérer tous les documents avec leur type
     */
    public function allWithType(): array
    {
        $sql = "SELECT d.*, t.libelle_type, t.duree_emprunt_jours 
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                ORDER BY d.titre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un document avec son type
     */
    public function findWithType(int $id): ?array
    {
        $sql = "SELECT d.*, t.libelle_type, t.duree_emprunt_jours, t.nb_renouvellements_max
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE d.id_document = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Récupérer les documents disponibles
     */
    public function findAvailable(): array
    {
        $sql = "SELECT d.*, t.libelle_type 
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE d.disponible = 1
                ORDER BY d.titre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Rechercher des documents
     */
    public function search(string $term, ?int $typeId = null): array
    {
        $sql = "SELECT d.*, t.libelle_type 
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE (d.titre LIKE :term OR d.auteur LIKE :term OR d.code_barre LIKE :term)";
        
        $params = ['term' => "%{$term}%"];

        if ($typeId) {
            $sql .= " AND d.id_type_document = :type_id";
            $params['type_id'] = $typeId;
        }

        $sql .= " ORDER BY d.titre";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Rechercher uniquement les documents disponibles
     */
    public function searchAvailable(string $term, ?int $typeId = null): array
    {
        $sql = "SELECT d.*, t.libelle_type 
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE d.disponible = 1
                AND (d.titre LIKE :term OR d.auteur LIKE :term OR d.code_barre LIKE :term)";
        
        $params = ['term' => "%{$term}%"];

        if ($typeId) {
            $sql .= " AND d.id_type_document = :type_id";
            $params['type_id'] = $typeId;
        }

        $sql .= " ORDER BY d.titre";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Trouver par code-barre
     */
    public function findByCodeBarre(string $codeBarre): ?array
    {
        return $this->findBy('code_barre', $codeBarre);
    }

    /**
     * Vérifier si un code-barre existe
     */
    public function codeBarreExists(string $codeBarre, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE code_barre = :code";
        
        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code' => $codeBarre, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code' => $codeBarre]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    /**
     * Récupérer les documents par type
     */
    public function findByType(int $typeId): array
    {
        $sql = "SELECT d.*, t.libelle_type 
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE d.id_type_document = :type_id
                ORDER BY d.titre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['type_id' => $typeId]);
        return $stmt->fetchAll();
    }

    /**
     * Mettre à jour la disponibilité
     */
    public function setDisponible(int $id, bool $disponible): bool
    {
        return $this->update($id, ['disponible' => $disponible ? 1 : 0]);
    }

    /**
     * Compter les documents disponibles
     */
    public function countAvailable(): int
    {
        return $this->countWhere('disponible', 1);
    }

    /**
     * Compter les documents empruntés
     */
    public function countEmpruntes(): int
    {
        return $this->countWhere('disponible', 0);
    }

    /**
     * Compter les documents par type
     */
    public function countByType(): array
    {
        $sql = "SELECT t.libelle_type, COUNT(d.id_document) as total
                FROM type_document t
                LEFT JOIN {$this->table} d ON t.id_type_document = d.id_type_document
                GROUP BY t.id_type_document, t.libelle_type
                ORDER BY t.libelle_type";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les derniers documents ajoutés
     */
    public function findRecent(int $limit = 10): array
    {
        $sql = "SELECT d.*, t.libelle_type 
                FROM {$this->table} d
                JOIN type_document t ON d.id_type_document = t.id_type_document
                ORDER BY d.id_document DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}