<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>documents" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Titre</span>
                <span class="detail-value"><?= htmlspecialchars($document['titre']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Auteur</span>
                <span class="detail-value"><?= htmlspecialchars($document['auteur'] ?? '-') ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Type</span>
                <span class="detail-value"><span class="badge badge-info"><?= htmlspecialchars($document['libelle_type']) ?></span></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Année de parution</span>
                <span class="detail-value"><?= $document['annee_parution'] ?? '-' ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Code-barre</span>
                <span class="detail-value"><?= htmlspecialchars($document['code_barre']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Emplacement</span>
                <span class="detail-value"><?= htmlspecialchars($document['emplacement'] ?? '-') ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Durée d'emprunt</span>
                <span class="detail-value"><?= $document['duree_emprunt_jours'] ?> jours</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Renouvellements max</span>
                <span class="detail-value"><?= $document['nb_renouvellements_max'] ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Statut</span>
                <span class="detail-value">
                    <?php if ($document['disponible']): ?>
                        <span class="badge badge-success">Disponible</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Emprunté</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        
        <?php if (!Session::isAdherent()): ?>
            <div class="form-actions">
                <a href="<?= BASE_URL ?>documents/edit/<?= $document['id_document'] ?>" class="btn btn-warning">Modifier</a>
                <?php if ($document['disponible']): ?>
                    <a href="<?= BASE_URL ?>documents/delete/<?= $document['id_document'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce document ?')">Supprimer</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>