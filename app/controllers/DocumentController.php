<?php
/**
 * DocumentController - Gestion des documents
 */

require_once MODELS_PATH . 'Document.php';
require_once MODELS_PATH . 'TypeDocument.php';

class DocumentController extends Controller
{
    private Document $documentModel;
    private TypeDocument $typeModel;

    public function __construct()
    {
        $this->documentModel = new Document();
        $this->typeModel = new TypeDocument();
    }

    /**
     * Liste des documents
     */
    public function index(): void
    {
        $this->requireLogin();

        $documents = $this->documentModel->allWithType();
        $types = $this->typeModel->all();

        $this->view('documents/index', [
            'title' => 'Gestion des documents',
            'documents' => $documents,
            'types' => $types
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create(): void
    {
        $this->requireStaff();

        $types = $this->typeModel->all();

        $this->view('documents/create', [
            'title' => 'Ajouter un document',
            'types' => $types
        ]);
    }

    /**
     * Enregistrer un nouveau document
     */
    public function store(): void
    {
        $this->requireStaff();

        if (!$this->isPost()) {
            $this->redirect('documents/create');
        }

        $data = [
            'titre' => $this->post('titre'),
            'auteur' => $this->post('auteur'),
            'annee_parution' => $this->post('annee_parution'),
            'code_barre' => $this->post('code_barre'),
            'emplacement' => $this->post('emplacement'),
            'id_type_document' => $this->post('id_type_document'),
            'disponible' => 1
        ];

        $errors = $this->validateDocument($data);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('documents/create');
        }

        if ($this->documentModel->codeBarreExists($data['code_barre'])) {
            Session::setFlash('error', 'Ce code-barre existe déjà.');
            $this->redirect('documents/create');
        }

        $id = $this->documentModel->create($data);

        if ($id) {
            Session::setFlash('success', 'Document ajouté avec succès.');
            $this->redirect('documents');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'ajout du document.');
            $this->redirect('documents/create');
        }
    }

    /**
     * Afficher un document
     */
    public function show(int $id): void
    {
        $this->requireLogin();

        $document = $this->documentModel->findWithType($id);

        if (!$document) {
            Session::setFlash('error', 'Document non trouvé.');
            $this->redirect('documents');
        }

        $this->view('documents/show', [
            'title' => $document['titre'],
            'document' => $document
        ]);
    }

    /**
     * Formulaire de modification
     */
    public function edit(int $id): void
    {
        $this->requireStaff();

        $document = $this->documentModel->find($id);

        if (!$document) {
            Session::setFlash('error', 'Document non trouvé.');
            $this->redirect('documents');
        }

        $types = $this->typeModel->all();

        $this->view('documents/edit', [
            'title' => 'Modifier le document',
            'document' => $document,
            'types' => $types
        ]);
    }

    /**
     * Mettre à jour un document
     */
    public function update(int $id): void
    {
        $this->requireStaff();

        if (!$this->isPost()) {
            $this->redirect('documents/edit/' . $id);
        }

        $document = $this->documentModel->find($id);

        if (!$document) {
            Session::setFlash('error', 'Document non trouvé.');
            $this->redirect('documents');
        }

        $data = [
            'titre' => $this->post('titre'),
            'auteur' => $this->post('auteur'),
            'annee_parution' => $this->post('annee_parution'),
            'code_barre' => $this->post('code_barre'),
            'emplacement' => $this->post('emplacement'),
            'id_type_document' => $this->post('id_type_document')
        ];

        $errors = $this->validateDocument($data);

        if (!empty($errors)) {
            Session::setFlash('error', implode('<br>', $errors));
            $this->redirect('documents/edit/' . $id);
        }

        if ($this->documentModel->codeBarreExists($data['code_barre'], $id)) {
            Session::setFlash('error', 'Ce code-barre existe déjà.');
            $this->redirect('documents/edit/' . $id);
        }

        if ($this->documentModel->update($id, $data)) {
            Session::setFlash('success', 'Document modifié avec succès.');
            $this->redirect('documents');
        } else {
            Session::setFlash('error', 'Erreur lors de la modification.');
            $this->redirect('documents/edit/' . $id);
        }
    }

    /**
     * Supprimer un document
     */
    public function delete(int $id): void
    {
        $this->requireStaff();

        $document = $this->documentModel->find($id);

        if (!$document) {
            Session::setFlash('error', 'Document non trouvé.');
            $this->redirect('documents');
        }

        if (!$document['disponible']) {
            Session::setFlash('error', 'Impossible de supprimer un document emprunté.');
            $this->redirect('documents');
        }

        if ($this->documentModel->delete($id)) {
            Session::setFlash('success', 'Document supprimé avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }

        $this->redirect('documents');
    }

    /**
     * Rechercher des documents
     */
    public function search(): void
    {
        $this->requireLogin();

        $term = $this->get('q', '');
        $typeId = $this->get('type') ? (int) $this->get('type') : null;

        $documents = [];
        if (!empty($term)) {
            $documents = $this->documentModel->search($term, $typeId);
        }

        $types = $this->typeModel->all();

        $this->view('documents/search', [
            'title' => 'Recherche de documents',
            'documents' => $documents,
            'types' => $types,
            'searchTerm' => $term,
            'selectedType' => $typeId
        ]);
    }

    /**
     * Valider les données d'un document
     */
    private function validateDocument(array $data): array
    {
        $errors = [];

        if (empty($data['titre'])) {
            $errors[] = 'Le titre est obligatoire.';
        }

        if (empty($data['code_barre'])) {
            $errors[] = 'Le code-barre est obligatoire.';
        }

        if (empty($data['id_type_document'])) {
            $errors[] = 'Le type de document est obligatoire.';
        }

        if (!empty($data['annee_parution'])) {
            $annee = (int) $data['annee_parution'];
            if ($annee < 1000 || $annee > date('Y')) {
                $errors[] = 'L\'année de parution n\'est pas valide.';
            }
        }

        return $errors;
    }
}