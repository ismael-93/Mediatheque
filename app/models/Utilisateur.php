<?php
/**
 * Model Utilisateur - Gestion des bibliothécaires et admins
 */

require_once MODELS_PATH . 'Model.php';

class Utilisateur extends Model
{
    protected string $table = 'utilisateur';
    protected string $primaryKey = 'id_utilisateur';

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    /**
     * Authentifier un utilisateur
     */
    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return null;
        }
        
        if (!$user['actif']) {
            return null;
        }
        
        if (password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        
        return null;
    }

    /**
     * Créer un utilisateur avec mot de passe hashé
     */
    public function createUser(array $data): int
    {
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        $data['date_creation'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['mot_de_passe' => $hash]);
    }

    /**
     * Récupérer tous les utilisateurs actifs
     */
    public function allActive(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE actif = 1 ORDER BY nom, prenom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les utilisateurs par rôle
     */
    public function findByRole(string $role): array
    {
        return $this->findAllBy('role', $role);
    }

    /**
     * Vérifier si un email existe déjà
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