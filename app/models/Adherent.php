<?php
/**
 * Model Adherent - Gestion des adhérents
 */

require_once MODELS_PATH . 'Model.php';

class Adherent extends Model
{
    protected string $table = 'adherent';
    protected string $primaryKey = 'id_adherent';

    /**
     * Trouver un adhérent par email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    /**
     * Trouver par numéro de carte
     */
    public function findByNumCarte(string $numCarte): ?array
    {
        return $this->findBy('numero_carte', $numCarte);
    }

    /**
     * Authentifier un adhérent
     */
    public function authenticate(string $email, string $password): ?array
    {
        $adherent = $this->findByEmail($email);
        
        if (!$adherent || !$adherent['actif']) {
            return null;
        }
        
        if (password_verify($password, $adherent['mot_de_passe'])) {
            return $adherent;
        }
        
        return null;
    }

    /**
     * Créer un adhérent
     */
    public function createAdherent(array $data): int
    {
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        $data['date_inscription'] = date('Y-m-d');
        $data['date_expiration'] = date('Y-m-d', strtotime('+1 year'));
        $data['numero_carte'] = $this->generateNumCarte();
        return $this->create($data);
    }

    /**
     * Générer un numéro de carte unique
     */
    private function generateNumCarte(): string
    {
        do {
            $numero = 'ADH-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while ($this->findByNumCarte($numero));
        
        return $numero;
    }

    /**
     * Récupérer tous les adhérents actifs
     */
    public function allActive(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE actif = 1 ORDER BY nom, prenom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les adhérents avec abonnement expiré
     */
    public function findExpired(): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE date_expiration < CURDATE() AND actif = 1 
                ORDER BY date_expiration";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Renouveler l'abonnement
     */
    public function renew(int $id): bool
    {
        $newDate = date('Y-m-d', strtotime('+1 year'));
        return $this->update($id, ['date_expiration' => $newDate]);
    }

    /**
     * Rechercher des adhérents
     */
    public function search(string $term): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE nom LIKE :term 
                OR prenom LIKE :term 
                OR email LIKE :term 
                OR numero_carte LIKE :term
                ORDER BY nom, prenom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['term' => "%{$term}%"]);
        return $stmt->fetchAll();
    }

    /**
     * Vérifier si l'email existe
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = :email";
        
        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}