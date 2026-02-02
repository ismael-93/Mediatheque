<?php
/**
 * AdherentController - Gestion des adhérents
 */

require_once MODELS_PATH . 'Adherent.php';
require_once MODELS_PATH . 'Emprunt.php';

class AdherentController extends Controller
{
    private Adherent $adherentModel;
    private Emprunt $empruntModel;

    public function __construct()
    {
        $this->adherentModel = new Adherent();
        $this->empruntModel = new Emprunt();
    }

    /**
     * Liste des adhérents
     */
    public function index(): void
    {
        $this->requireStaff();

        $adherents = $this->adherentModel->all();

        $this->view('adherents/index', [
            'title' => 'Gestion des adhérents',
            'adherents' => $adherents
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create(): void
    {
        $this->requireStaff();

        $this->view('adherents/create', [
            'title' => 'Ajouter un adhérent'
        ]);
    }

    /**
     * Enregistrer un nouvel adhérent
     */
    public function store(): void
    {
        $this->requireStaff();

        if (!$this->isPost()) {
            $this->redirect('adherents/create');
        }

        $data = [
            'nom' => $this->post('nom'),
            'prenom' => $this->post('prenom'),
            'email' => $this->post('email'),
            'telephone' => $this->post('telephone'),
            'adresse' => $this->post('adresse'),
            'mot_de_passe' => $this->post('mot_de_passe')
        ];

        $errors = $this->validateAdherent($data, true);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('adherents/create');
        }

        if ($this->adherentModel->emailExists($data['email'])) {
            Session::setFlash('error', 'Cet email est déjà utilisé.');
            $this->redirect('adherents/create');
        }

        $id = $this->adherentModel->createAdherent($data);

        if ($id) {
            Session::setFlash('success', 'Adhérent ajouté avec succès.');
            $this->redirect('adherents');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'ajout.');
            $this->redirect('adherents/create');
        }
    }

    /**
     * Afficher un adhérent
     */
    public function show(int $id): void
    {
        $this->requireStaff();

        $adherent = $this->adherentModel->find($id);

        if (!$adherent) {
            Session::setFlash('error', 'Adhérent non trouvé.');
            $this->redirect('adherents');
        }

        $emprunts = $this->empruntModel->findByAdherent($id);
        $empruntsEnCours = $this->empruntModel->findEnCoursByAdherent($id);

        $this->view('adherents/show', [
            'title' => $adherent['prenom'] . ' ' . $adherent['nom'],
            'adherent' => $adherent,
            'emprunts' => $emprunts,
            'empruntsEnCours' => $empruntsEnCours
        ]);
    }

    /**
     * Formulaire de modification
     */
    public function edit(int $id): void
    {
        $this->requireStaff();

        $adherent = $this->adherentModel->find($id);

        if (!$adherent) {
            Session::setFlash('error', 'Adhérent non trouvé.');
            $this->redirect('adherents');
        }

        $this->view('adherents/edit', [
            'title' => 'Modifier l\'adhérent',
            'adherent' => $adherent
        ]);
    }

    /**
     * Mettre à jour un adhérent
     */
    public function update(int $id): void
    {
        $this->requireStaff();

        if (!$this->isPost()) {
            $this->redirect('adherents/edit/' . $id);
        }

        $adherent = $this->adherentModel->find($id);

        if (!$adherent) {
            Session::setFlash('error', 'Adhérent non trouvé.');
            $this->redirect('adherents');
        }

        $data = [
            'nom' => $this->post('nom'),
            'prenom' => $this->post('prenom'),
            'email' => $this->post('email'),
            'telephone' => $this->post('telephone'),
            'adresse' => $this->post('adresse'),
            'actif' => $this->post('actif') ? 1 : 0
        ];

        $errors = $this->validateAdherent($data, false);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('adherents/edit/' . $id);
        }

        if ($this->adherentModel->emailExists($data['email'], $id)) {
            Session::setFlash('error', 'Cet email est déjà utilisé.');
            $this->redirect('adherents/edit/' . $id);
        }

        $newPassword = $this->post('mot_de_passe');
        if (!empty($newPassword)) {
            $data['mot_de_passe'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if ($this->adherentModel->update($id, $data)) {
            Session::setFlash('success', 'Adhérent modifié avec succès.');
            $this->redirect('adherents');
        } else {
            Session::setFlash('error', 'Erreur lors de la modification.');
            $this->redirect('adherents/edit/' . $id);
        }
    }

    /**
     * Supprimer un adhérent
     */
    public function delete(int $id): void
    {
        $this->requireStaff();

        $adherent = $this->adherentModel->find($id);

        if (!$adherent) {
            Session::setFlash('error', 'Adhérent non trouvé.');
            $this->redirect('adherents');
        }

        $empruntsEnCours = $this->empruntModel->countEnCoursByAdherent($id);
        if ($empruntsEnCours > 0) {
            Session::setFlash('error', 'Impossible de supprimer : l\'adhérent a des emprunts en cours.');
            $this->redirect('adherents');
        }

        if ($this->adherentModel->delete($id)) {
            Session::setFlash('success', 'Adhérent supprimé avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('adherents');
    }

    /**
     * Valider les données d'un adhérent
     */
    private function validateAdherent(array $data, bool $isNew = true): array
    {
        $errors = [];

        if (empty($data['nom'])) {
            $errors[] = 'Le nom est obligatoire.';
        }

        if (empty($data['prenom'])) {
            $errors[] = 'Le prénom est obligatoire.';
        }

        if (empty($data['email'])) {
            $errors[] = 'L\'email est obligatoire.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email n\'est pas valide.';
        }

        if ($isNew && empty($data['mot_de_passe'])) {
            $errors[] = 'Le mot de passe est obligatoire.';
        }

        if (!empty($data['mot_de_passe']) && strlen($data['mot_de_passe']) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        return $errors;
    }
}