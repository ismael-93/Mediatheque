<?php
/**
 * Model Emprunt - Gestion des emprunts
 */

require_once MODELS_PATH . 'Model.php';

class Emprunt extends Model
{
    protected string $table = 'emprunt';
    protected string $primaryKey = 'id_emprunt';

    /**
     * Récupérer tous les emprunts avec détails
     */
    public function allWithDetails(): array
    {
        $sql = "SELECT e.*, 
                       a.nom AS adherent_nom, 
                       a.prenom AS adherent_prenom,
                       a.numero_carte,
                       d.titre AS document_titre,
                       d.auteur AS document_auteur,
                       t.libelle_type,
                       u.nom AS utilisateur_nom,
                       u.prenom AS utilisateur_prenom
                FROM {$this->table} e
                JOIN adherent a ON e.id_adherent = a.id_adherent
                JOIN document d ON e.id_document = d.id_document
                JOIN type_document t ON d.id_type_document = t.id_type_document
                JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
                ORDER BY e.date_emprunt DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un emprunt avec détails
     */
    public function findWithDetails(int $id): ?array
    {
        $sql = "SELECT e.*, 
                       a.nom AS adherent_nom, 
                       a.prenom AS adherent_prenom,
                       a.numero_carte,
                       a.email AS adherent_email,
                       d.titre AS document_titre,
                       d.auteur AS document_auteur,
                       d.code_barre,
                       t.libelle_type,
                       t.duree_emprunt_jours,
                       t.nb_renouvellements_max,
                       u.nom AS utilisateur_nom,
                       u.prenom AS utilisateur_prenom
                FROM {$this->table} e
                JOIN adherent a ON e.id_adherent = a.id_adherent
                JOIN document d ON e.id_document = d.id_document
                JOIN type_document t ON d.id_type_document = t.id_type_document
                JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
                WHERE e.id_emprunt = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Récupérer les emprunts en cours
     */
    public function findEnCours(): array
    {
        $sql = "SELECT e.*, 
                       a.nom AS adherent_nom, 
                       a.prenom AS adherent_prenom,
                       a.numero_carte,
                       d.titre AS document_titre,
                       d.auteur AS document_auteur,
                       t.libelle_type,
                       DATEDIFF(e.date_retour_prevue, CURDATE()) AS jours_restants
                FROM {$this->table} e
                JOIN adherent a ON e.id_adherent = a.id_adherent
                JOIN document d ON e.id_document = d.id_document
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE e.statut = 'en_cours'
                ORDER BY e.date_retour_prevue ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les emprunts en retard
     */
    public function findEnRetard(): array
    {
        $sql = "SELECT e.*, 
                       a.nom AS adherent_nom, 
                       a.prenom AS adherent_prenom,
                       a.numero_carte,
                       a.email AS adherent_email,
                       a.telephone AS adherent_telephone,
                       d.titre AS document_titre,
                       d.auteur AS document_auteur,
                       t.libelle_type,
                       DATEDIFF(CURDATE(), e.date_retour_prevue) AS jours_retard
                FROM {$this->table} e
                JOIN adherent a ON e.id_adherent = a.id_adherent
                JOIN document d ON e.id_document = d.id_document
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE e.statut = 'en_cours' 
                AND e.date_retour_prevue < CURDATE()
                ORDER BY jours_retard DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les emprunts d'un adhérent
     */
    public function findByAdherent(int $idAdherent): array
    {
        $sql = "SELECT e.*, 
                       d.titre AS document_titre,
                       d.auteur AS document_auteur,
                       t.libelle_type
                FROM {$this->table} e
                JOIN document d ON e.id_document = d.id_document
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE e.id_adherent = :id
                ORDER BY e.date_emprunt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idAdherent]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les emprunts en cours d'un adhérent
     */
    public function findEnCoursByAdherent(int $idAdherent): array
    {
        $sql = "SELECT e.*, 
                       d.titre AS document_titre,
                       d.auteur AS document_auteur,
                       t.libelle_type,
                       DATEDIFF(e.date_retour_prevue, CURDATE()) AS jours_restants
                FROM {$this->table} e
                JOIN document d ON e.id_document = d.id_document
                JOIN type_document t ON d.id_type_document = t.id_type_document
                WHERE e.id_adherent = :id AND e.statut = 'en_cours'
                ORDER BY e.date_retour_prevue ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idAdherent]);
        return $stmt->fetchAll();
    }

    /**
     * Créer un emprunt
     */
    public function createEmprunt(int $idAdherent, int $idDocument, int $idUtilisateur, int $dureeJours): int
    {
        $data = [
            'id_adherent' => $idAdherent,
            'id_document' => $idDocument,
            'id_utilisateur' => $idUtilisateur,
            'date_emprunt' => date('Y-m-d'),
            'date_retour_prevue' => date('Y-m-d', strtotime("+{$dureeJours} days")),
            'statut' => 'en_cours'
        ];

        $idEmprunt = $this->create($data);

        // Mettre le document comme non disponible
        $this->setDocumentDisponible($idDocument, false);

        return $idEmprunt;
    }

    /**
     * Enregistrer un retour
     */
    public function retourner(int $idEmprunt, ?string $remarques = null): bool
    {
        $emprunt = $this->find($idEmprunt);
        
        if (!$emprunt) {
            return false;
        }

        $data = [
            'date_retour_effective' => date('Y-m-d'),
            'statut' => 'retourne'
        ];

        if ($remarques) {
            $data['remarques'] = $remarques;
        }

        $this->update($idEmprunt, $data);

        // Remettre le document comme disponible
        $this->setDocumentDisponible($emprunt['id_document'], true);

        return true;
    }

    /**
     * Renouveler un emprunt
     */
    public function renouveler(int $idEmprunt): bool
    {
        $emprunt = $this->findWithDetails($idEmprunt);
        
        if (!$emprunt) {
            return false;
        }

        // Vérifier si renouvellement possible
        if ($emprunt['nombre_renouvellements_effectue'] >= $emprunt['nb_renouvellements_max']) {
            return false;
        }

        // Calculer nouvelle date
        $nouvelleDate = date('Y-m-d', strtotime($emprunt['date_retour_prevue'] . " +{$emprunt['duree_emprunt_jours']} days"));

        return $this->update($idEmprunt, [
            'date_retour_prevue' => $nouvelleDate,
            'nombre_renouvellements_effectue' => $emprunt['nombre_renouvellements_effectue'] + 1
        ]);
    }

    /**
     * Mettre à jour la disponibilité d'un document
     */
    private function setDocumentDisponible(int $idDocument, bool $disponible): void
    {
        $sql = "UPDATE document SET disponible = :dispo WHERE id_document = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'dispo' => $disponible ? 1 : 0,
            'id' => $idDocument
        ]);
    }

    /**
     * Vérifier si un document est déjà emprunté
     */
    public function isDocumentEmprunte(int $idDocument): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE id_document = :id AND statut = 'en_cours'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idDocument]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    /**
     * Compter les emprunts en cours d'un adhérent
     */
    public function countEnCoursByAdherent(int $idAdherent): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE id_adherent = :id AND statut = 'en_cours'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idAdherent]);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    /**
     * Mettre à jour les statuts en retard
     */
    public function updateStatutsRetard(): int
    {
        $sql = "UPDATE {$this->table} 
                SET statut = 'retard' 
                WHERE statut = 'en_cours' 
                AND date_retour_prevue < CURDATE()";
        $stmt = $this->db->exec($sql);
        return $stmt;
    }

    /**
     * Statistiques des emprunts
     */
    public function getStats(): array
    {
        $stats = [];

        // Total emprunts
        $stats['total'] = $this->count();

        // En cours
        $stats['en_cours'] = $this->countWhere('statut', 'en_cours');

        // En retard
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE statut = 'en_cours' AND date_retour_prevue < CURDATE()";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['en_retard'] = (int) $result['total'];

        // Retournés
        $stats['retournes'] = $this->countWhere('statut', 'retourne');

        return $stats;
    }
}