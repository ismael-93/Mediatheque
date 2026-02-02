<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>types-documents" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>types-documents/update/<?= $type['id_type_document'] ?>" method="POST">
            <div class="form-group">
                <label for="libelle_type">Libellé *</label>
                <input type="text" id="libelle_type" name="libelle_type" required class="form-control" value="<?= htmlspecialchars($type['libelle_type']) ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duree_emprunt_jours">Durée d'emprunt (jours) *</label>
                    <input type="number" id="duree_emprunt_jours" name="duree_emprunt_jours" required class="form-control" min="1" value="<?= $type['duree_emprunt_jours'] ?>">
                </div>
                
                <div class="form-group">
                    <label for="nb_renouvellements_max">Nombre de renouvellements max *</label>
                    <input type="number" id="nb_renouvellements_max" name="nb_renouvellements_max" required class="form-control" min="0" value="<?= $type['nb_renouvellements_max'] ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= BASE_URL ?>types-documents" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>