<?php
/**
 * TypeDocumentController - Gestion des types de documents
 */

require_once MODELS_PATH . 'TypeDocument.php';

class TypeDocumentController extends Controller
{
    private TypeDocument $typeModel;

    public function __construct()
    {
        $this->typeModel = new TypeDocument();
    }

    public function index(): void
    {
        $this->requireStaff();
        $types = $this->typeModel->all();
        $this->view('types_documents/index', [
            'title' => 'Types de documents',
            'types' => $types
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->view('types_documents/create', [
            'title' => 'Ajouter un type'
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('types-documents/create');
        }

        $data = [
            'libelle_type' => $this->post('libelle_type'),
            'duree_emprunt_jours' => (int) $this->post('duree_emprunt_jours'),
            'nb_renouvellements_max' => (int) $this->post('nb_renouvellements_max')
        ];

        $errors = $this->validate($data);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('types-documents/create');
        }

        if ($this->typeModel->findByLibelle($data['libelle_type'])) {
            Session::setFlash('error', 'Ce type existe déjà.');
            $this->redirect('types-documents/create');
        }

        $id = $this->typeModel->create($data);

        if ($id) {
            Session::setFlash('success', 'Type ajouté avec succès.');
            $this->redirect('types-documents');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'ajout.');
            $this->redirect('types-documents/create');
        }
    }

    public function edit(int $id): void
    {
        $this->requireAdmin();
        $type = $this->typeModel->find($id);

        if (!$type) {
            Session::setFlash('error', 'Type non trouvé.');
            $this->redirect('types-documents');
        }

        $this->view('types_documents/edit', [
            'title' => 'Modifier le type',
            'type' => $type
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('types-documents/edit/' . $id);
        }

        $type = $this->typeModel->find($id);

        if (!$type) {
            Session::setFlash('error', 'Type non trouvé.');
            $this->redirect('types-documents');
        }

        $data = [
            'libelle_type' => $this->post('libelle_type'),
            'duree_emprunt_jours' => (int) $this->post('duree_emprunt_jours'),
            'nb_renouvellements_max' => (int) $this->post('nb_renouvellements_max')
        ];

        $errors = $this->validate($data);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('types-documents/edit/' . $id);
        }

        if ($this->typeModel->update($id, $data)) {
            Session::setFlash('success', 'Type modifié avec succès.');
            $this->redirect('types-documents');
        } else {
            Session::setFlash('error', 'Erreur lors de la modification.');
            $this->redirect('types-documents/edit/' . $id);
        }
    }

    public function delete(int $id): void
    {
        $this->requireAdmin();
        $type = $this->typeModel->find($id);

        if (!$type) {
            Session::setFlash('error', 'Type non trouvé.');
            $this->redirect('types-documents');
        }

        if ($this->typeModel->isUsed($id)) {
            Session::setFlash('error', 'Impossible de supprimer : ce type est utilisé.');
            $this->redirect('types-documents');
        }

        if ($this->typeModel->delete($id)) {
            Session::setFlash('success', 'Type supprimé avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('types-documents');
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['libelle_type'])) {
            $errors[] = 'Le libellé est obligatoire.';
        }

        if ($data['duree_emprunt_jours'] < 1) {
            $errors[] = 'La durée d\'emprunt doit être supérieure à 0.';
        }

        if ($data['nb_renouvellements_max'] < 0) {
            $errors[] = 'Le nombre de renouvellements ne peut pas être négatif.';
        }

        return $errors;
    }
}