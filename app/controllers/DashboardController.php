<?php
/**
 * DashboardController - Tableau de bord
 */

require_once MODELS_PATH . 'Document.php';
require_once MODELS_PATH . 'Adherent.php';
require_once MODELS_PATH . 'Emprunt.php';
require_once MODELS_PATH . 'Utilisateur.php';

class DashboardController extends Controller
{
    private Document $documentModel;
    private Adherent $adherentModel;
    private Emprunt $empruntModel;

    public function __construct()
    {
        $this->documentModel = new Document();
        $this->adherentModel = new Adherent();
        $this->empruntModel = new Emprunt();
    }

    /**
     * Afficher le tableau de bord
     */
    public function index(): void
    {
        $this->requireLogin();

        $data = [];

        if (Session::isAdherent()) {
            // Dashboard adhérent
            $data = $this->getAdherentDashboard();
            $this->view('dashboard/index', $data);
        } else {
            // Dashboard bibliothécaire/admin
            $data = $this->getStaffDashboard();
            $this->view('dashboard/index', $data);
        }
    }

    /**
     * Données pour le dashboard adhérent
     */
    private function getAdherentDashboard(): array
    {
        $adherentId = Session::get('adherent_id');

        return [
            'title' => 'Mon espace',
            'empruntsEnCours' => $this->empruntModel->findEnCoursByAdherent($adherentId),
            'historiqueEmprunts' => $this->empruntModel->findByAdherent($adherentId),
            'nbEmpruntsEnCours' => $this->empruntModel->countEnCoursByAdherent($adherentId)
        ];
    }

    /**
     * Données pour le dashboard staff
     */
    private function getStaffDashboard(): array
    {
        return [
            'title' => 'Tableau de bord',
            'stats' => [
                'totalDocuments' => $this->documentModel->count(),
                'documentsDisponibles' => $this->documentModel->countAvailable(),
                'documentsEmpruntes' => $this->documentModel->countEmpruntes(),
                'totalAdherents' => $this->adherentModel->count(),
                'empruntsEnCours' => $this->empruntModel->countWhere('statut', 'en_cours'),
                'empruntsRetard' => count($this->empruntModel->findEnRetard())
            ],
            'empruntsRetard' => $this->empruntModel->findEnRetard(),
            'derniersDocuments' => $this->documentModel->findRecent(5),
            'documentsByType' => $this->documentModel->countByType()
        ];
    }
}