<?php
/**
 * UtilisateurController - Gestion des utilisateurs (admin/bibliothécaires)
 */

require_once MODELS_PATH . 'Utilisateur.php';

class UtilisateurController extends Controller
{
    private Utilisateur $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $utilisateurs = $this->utilisateurModel->all();
        $this->view('utilisateurs/index', [
            'title' => 'Gestion des utilisateurs',
            'utilisateurs' => $utilisateurs
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->view('utilisateurs/create', [
            'title' => 'Ajouter un utilisateur'
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('utilisateurs/create');
        }

        $data = [
            'nom' => $this->post('nom'),
            'prenom' => $this->post('prenom'),
            'email' => $this->post('email'),
            'mot_de_passe' => $this->post('mot_de_passe'),
            'role' => $this->post('role')
        ];

        $errors = $this->validate($data, true);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('utilisateurs/create');
        }

        if ($this->utilisateurModel->emailExists($data['email'])) {
            Session::setFlash('error', 'Cet email est déjà utilisé.');
            $this->redirect('utilisateurs/create');
        }

        $id = $this->utilisateurModel->createUser($data);

        if ($id) {
            Session::setFlash('success', 'Utilisateur ajouté avec succès.');
            $this->redirect('utilisateurs');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'ajout.');
            $this->redirect('utilisateurs/create');
        }
    }

    public function edit(int $id): void
    {
        $this->requireAdmin();
        $utilisateur = $this->utilisateurModel->find($id);

        if (!$utilisateur) {
            Session::setFlash('error', 'Utilisateur non trouvé.');
            $this->redirect('utilisateurs');
        }

        $this->view('utilisateurs/edit', [
            'title' => 'Modifier l\'utilisateur',
            'utilisateur' => $utilisateur
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('utilisateurs/edit/' . $id);
        }

        $utilisateur = $this->utilisateurModel->find($id);

        if (!$utilisateur) {
            Session::setFlash('error', 'Utilisateur non trouvé.');
            $this->redirect('utilisateurs');
        }

        $data = [
            'nom' => $this->post('nom'),
            'prenom' => $this->post('prenom'),
            'email' => $this->post('email'),
            'role' => $this->post('role'),
            'actif' => $this->post('actif') ? 1 : 0
        ];

        $errors = $this->validate($data, false);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('utilisateurs/edit/' . $id);
        }

        if ($this->utilisateurModel->emailExists($data['email'], $id)) {
            Session::setFlash('error', 'Cet email est déjà utilisé.');
            $this->redirect('utilisateurs/edit/' . $id);
        }

        $newPassword = $this->post('mot_de_passe');
        if (!empty($newPassword)) {
            $data['mot_de_passe'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if ($this->utilisateurModel->update($id, $data)) {
            Session::setFlash('success', 'Utilisateur modifié avec succès.');
            $this->redirect('utilisateurs');
        } else {
            Session::setFlash('error', 'Erreur lors de la modification.');
            $this->redirect('utilisateurs/edit/' . $id);
        }
    }

    public function delete(int $id): void
    {
        $this->requireAdmin();

        $utilisateur = $this->utilisateurModel->find($id);

        if (!$utilisateur) {
            Session::setFlash('error', 'Utilisateur non trouvé.');
            $this->redirect('utilisateurs');
        }

        $currentUserId = Session::get('user_id');
        if ($id === $currentUserId) {
            Session::setFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            $this->redirect('utilisateurs');
        }

        if ($this->utilisateurModel->delete($id)) {
            Session::setFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('utilisateurs');
    }

    private function validate(array $data, bool $isNew = true): array
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

        if (empty($data['role'])) {
            $errors[] = 'Le rôle est obligatoire.';
        } elseif (!in_array($data['role'], ['administrateur', 'bibliothecaire'])) {
            $errors[] = 'Le rôle n\'est pas valide.';
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