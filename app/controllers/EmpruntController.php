<?php
/**
 * EmpruntController - Gestion des emprunts
 */

require_once MODELS_PATH . 'Emprunt.php';
require_once MODELS_PATH . 'Document.php';
require_once MODELS_PATH . 'Adherent.php';

class EmpruntController extends Controller
{
    private Emprunt $empruntModel;
    private Document $documentModel;
    private Adherent $adherentModel;

    public function __construct()
    {
        $this->empruntModel = new Emprunt();
        $this->documentModel = new Document();
        $this->adherentModel = new Adherent();
    }

    public function index(): void
    {
        $this->requireStaff();
        $emprunts = $this->empruntModel->findEnCours();
        $this->view('emprunts/index', [
            'title' => 'Emprunts en cours',
            'emprunts' => $emprunts
        ]);
    }

    public function create(): void
    {
        $this->requireStaff();
        $documents = $this->documentModel->findAvailable();
        $adherents = $this->adherentModel->allActive();
        $this->view('emprunts/create', [
            'title' => 'Nouvel emprunt',
            'documents' => $documents,
            'adherents' => $adherents
        ]);
    }

    public function store(): void
    {
        $this->requireStaff();

        if (!$this->isPost()) {
            $this->redirect('emprunts/create');
        }

        $idAdherent = (int) $this->post('id_adherent');
        $idDocument = (int) $this->post('id_document');
        $idUtilisateur = Session::get('user_id');

        if (empty($idAdherent) || empty($idDocument)) {
            Session::setFlash('error', 'Veuillez sélectionner un adhérent et un document.');
            $this->redirect('emprunts/create');
        }

        $adherent = $this->adherentModel->find($idAdherent);
        if (!$adherent || !$adherent['actif']) {
            Session::setFlash('error', 'Adhérent non trouvé ou inactif.');
            $this->redirect('emprunts/create');
        }

        if (strtotime($adherent['date_expiration']) < time()) {
            Session::setFlash('error', 'L\'abonnement de cet adhérent a expiré.');
            $this->redirect('emprunts/create');
        }

        $document = $this->documentModel->findWithType($idDocument);
        if (!$document) {
            Session::setFlash('error', 'Document non trouvé.');
            $this->redirect('emprunts/create');
        }

        if (!$document['disponible']) {
            Session::setFlash('error', 'Ce document n\'est pas disponible.');
            $this->redirect('emprunts/create');
        }

        $dureeJours = $document['duree_emprunt_jours'];
        $idEmprunt = $this->empruntModel->createEmprunt($idAdherent, $idDocument, $idUtilisateur, $dureeJours);

        if ($idEmprunt) {
            Session::setFlash('success', 'Emprunt enregistré avec succès.');
            $this->redirect('emprunts');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'enregistrement.');
            $this->redirect('emprunts/create');
        }
    }

    public function retour(int $id): void
    {
        $this->requireStaff();
        $emprunt = $this->empruntModel->findWithDetails($id);

        if (!$emprunt) {
            Session::setFlash('error', 'Emprunt non trouvé.');
            $this->redirect('emprunts');
        }

        if ($emprunt['statut'] === 'retourne') {
            Session::setFlash('error', 'Ce document a déjà été retourné.');
            $this->redirect('emprunts');
        }

        $this->view('emprunts/retour', [
            'title' => 'Retour de document',
            'emprunt' => $emprunt
        ]);
    }

    public function retourner(int $id): void
    {
        $this->requireStaff();

        if (!$this->isPost()) {
            $this->redirect('emprunts/retour/' . $id);
        }

        $emprunt = $this->empruntModel->find($id);

        if (!$emprunt) {
            Session::setFlash('error', 'Emprunt non trouvé.');
            $this->redirect('emprunts');
        }

        if ($emprunt['statut'] === 'retourne') {
            Session::setFlash('error', 'Ce document a déjà été retourné.');
            $this->redirect('emprunts');
        }

        $remarques = $this->post('remarques');

        if ($this->empruntModel->retourner($id, $remarques)) {
            Session::setFlash('success', 'Retour enregistré avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors du retour.');
        }

        $this->redirect('emprunts');
    }

    public function renouveler(int $id): void
    {
        $this->requireStaff();
        $emprunt = $this->empruntModel->findWithDetails($id);

        if (!$emprunt) {
            Session::setFlash('error', 'Emprunt non trouvé.');
            $this->redirect('emprunts');
        }

        if ($emprunt['statut'] !== 'en_cours') {
            Session::setFlash('error', 'Cet emprunt ne peut pas être renouvelé.');
            $this->redirect('emprunts');
        }

        if ($emprunt['nombre_renouvellements_effectue'] >= $emprunt['nb_renouvellements_max']) {
            Session::setFlash('error', 'Nombre maximum de renouvellements atteint.');
            $this->redirect('emprunts');
        }

        if ($this->empruntModel->renouveler($id)) {
            Session::setFlash('success', 'Emprunt renouvelé avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors du renouvellement.');
        }

        $this->redirect('emprunts');
    }

    public function historique(): void
    {
        $this->requireStaff();
        $emprunts = $this->empruntModel->allWithDetails();
        $this->view('emprunts/historique', [
            'title' => 'Historique des emprunts',
            'emprunts' => $emprunts
        ]);
    }

    public function retards(): void
    {
        $this->requireStaff();
        $emprunts = $this->empruntModel->findEnRetard();
        $this->view('emprunts/retards', [
            'title' => 'Emprunts en retard',
            'emprunts' => $emprunts
        ]);
    }
}