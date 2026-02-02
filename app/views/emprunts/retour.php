<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>emprunts" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Détails de l'emprunt</h2>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Adhérent</span>
                <span class="detail-value"><?= htmlspecialchars($emprunt['adherent_prenom'] . ' ' . $emprunt['adherent_nom']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">N° Carte</span>
                <span class="detail-value"><?= htmlspecialchars($emprunt['numero_carte']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Document</span>
                <span class="detail-value"><?= htmlspecialchars($emprunt['document_titre']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Type</span>
                <span class="detail-value"><span class="badge badge-info"><?= htmlspecialchars($emprunt['libelle_type']) ?></span></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date d'emprunt</span>
                <span class="detail-value"><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date de retour prévue</span>
                <span class="detail-value"><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Statut</span>
                <span class="detail-value">
                    <?php 
                    $joursRestants = (strtotime($emprunt['date_retour_prevue']) - time()) / 86400;
                    if ($joursRestants < 0): ?>
                        <span class="badge badge-danger">En retard de <?= abs(floor($joursRestants)) ?> jours</span>
                    <?php else: ?>
                        <span class="badge badge-success">Dans les temps</span>
                    <?php endif; ?>
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Renouvellements</span>
                <span class="detail-value"><?= $emprunt['nombre_renouvellements_effectue'] ?> / <?= $emprunt['nb_renouvellements_max'] ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Enregistrer le retour</h2>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>emprunts/retourner/<?= $emprunt['id_emprunt'] ?>" method="POST">
            <div class="form-group">
                <label for="remarques">Remarques (optionnel)</label>
                <textarea id="remarques" name="remarques" class="form-control" rows="3" placeholder="État du document, observations..."></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Confirmer le retour</button>
                <a href="<?= BASE_URL ?>emprunts" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>