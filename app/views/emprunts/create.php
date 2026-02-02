<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>emprunts" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>emprunts/store" method="POST">
            <div class="form-group">
                <label for="id_adherent">Adhérent *</label>
                <select id="id_adherent" name="id_adherent" required class="form-control">
                    <option value="">-- Sélectionner un adhérent --</option>
                    <?php foreach ($adherents as $adherent): ?>
                        <option value="<?= $adherent['id_adherent'] ?>">
                            <?= htmlspecialchars($adherent['numero_carte'] . ' - ' . $adherent['prenom'] . ' ' . $adherent['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_document">Document *</label>
                <select id="id_document" name="id_document" required class="form-control">
                    <option value="">-- Sélectionner un document --</option>
                    <?php foreach ($documents as $document): ?>
                        <option value="<?= $document['id_document'] ?>">
                            <?= htmlspecialchars($document['titre'] . ' (' . $document['libelle_type'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($documents)): ?>
                    <small class="form-text text-danger">Aucun document disponible.</small>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" <?= empty($documents) ? 'disabled' : '' ?>>Enregistrer l'emprunt</button>
                <a href="<?= BASE_URL ?>emprunts" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>