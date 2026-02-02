<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>documents" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>documents/update/<?= $document['id_document'] ?>" method="POST">
            <div class="form-group">
                <label for="titre">Titre *</label>
                <input type="text" id="titre" name="titre" required class="form-control" value="<?= htmlspecialchars($document['titre']) ?>">
            </div>
            
            <div class="form-group">
                <label for="auteur">Auteur</label>
                <input type="text" id="auteur" name="auteur" class="form-control" value="<?= htmlspecialchars($document['auteur'] ?? '') ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="annee_parution">Année de parution</label>
                    <input type="number" id="annee_parution" name="annee_parution" min="1000" max="<?= date('Y') ?>" class="form-control" value="<?= $document['annee_parution'] ?? '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="code_barre">Code-barre *</label>
                    <input type="text" id="code_barre" name="code_barre" required class="form-control" value="<?= htmlspecialchars($document['code_barre']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="id_type_document">Type de document *</label>
                    <select id="id_type_document" name="id_type_document" required class="form-control">
                        <?php foreach ($types as $type): ?>
                            <option value="<?= $type['id_type_document'] ?>" <?= $type['id_type_document'] == $document['id_type_document'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['libelle_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="emplacement">Emplacement</label>
                    <input type="text" id="emplacement" name="emplacement" class="form-control" value="<?= htmlspecialchars($document['emplacement'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= BASE_URL ?>documents" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>