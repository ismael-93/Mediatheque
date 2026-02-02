<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>adherents" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Informations</h2>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">N° Carte</span>
                <span class="detail-value"><?= htmlspecialchars($adherent['numero_carte'] ?? '-') ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Nom</span>
                <span class="detail-value"><?= htmlspecialchars($adherent['nom']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Prénom</span>
                <span class="detail-value"><?= htmlspecialchars($adherent['prenom']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?= htmlspecialchars($adherent['email']) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Téléphone</span>
                <span class="detail-value"><?= htmlspecialchars($adherent['telephone'] ?? '-') ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Adresse</span>
                <span class="detail-value"><?= nl2br(htmlspecialchars($adherent['adresse'] ?? '-')) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date d'inscription</span>
                <span class="detail-value"><?= date('d/m/Y', strtotime($adherent['date_inscription'])) ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date d'expiration</span>
                <span class="detail-value">
                    <?php 
                    $expiration = strtotime($adherent['date_expiration']);
                    $now = time();
                    if ($expiration < $now): ?>
                        <span class="badge badge-danger"><?= date('d/m/Y', $expiration) ?> (Expiré)</span>
                    <?php elseif ($expiration < strtotime('+30 days')): ?>
                        <span class="badge badge-warning"><?= date('d/m/Y', $expiration) ?></span>
                    <?php else: ?>
                        <span class="badge badge-success"><?= date('d/m/Y', $expiration) ?></span>
                    <?php endif; ?>
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Statut</span>
                <span class="detail-value">
                    <?php if ($adherent['actif']): ?>
                        <span class="badge badge-success">Actif</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactif</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="<?= BASE_URL ?>adherents/edit/<?= $adherent['id_adherent'] ?>" class="btn btn-warning">Modifier</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Emprunts en cours (<?= count($empruntsEnCours) ?>)</h2>
    </div>
    <div class="card-body">
        <?php if (empty($empruntsEnCours)): ?>
            <p class="text-muted">Aucun emprunt en cours.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Date emprunt</th>
                        <th>Date retour prévue</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empruntsEnCours as $emprunt): ?>
                        <tr>
                            <td><?= htmlspecialchars($emprunt['document_titre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></td>
                            <td>
                                <?php if ($emprunt['jours_restants'] < 0): ?>
                                    <span class="badge badge-danger">En retard (<?= abs($emprunt['jours_restants']) ?> jours)</span>
                                <?php elseif ($emprunt['jours_restants'] <= 3): ?>
                                    <span class="badge badge-warning"><?= $emprunt['jours_restants'] ?> jours restants</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= $emprunt['jours_restants'] ?> jours restants</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>emprunts/retour/<?= $emprunt['id_emprunt'] ?>" class="btn btn-sm btn-primary">Retour</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Historique des emprunts (<?= count($emprunts) ?>)</h2>
    </div>
    <div class="card-body">
        <?php if (empty($emprunts)): ?>
            <p class="text-muted">Aucun historique.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Date emprunt</th>
                        <th>Date retour</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?= htmlspecialchars($emprunt['document_titre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                            <td><?= $emprunt['date_retour_effective'] ? date('d/m/Y', strtotime($emprunt['date_retour_effective'])) : '-' ?></td>
                            <td>
                                <?php if ($emprunt['statut'] === 'retourne'): ?>
                                    <span class="badge badge-success">Retourné</span>
                                <?php elseif ($emprunt['statut'] === 'en_cours'): ?>
                                    <span class="badge badge-info">En cours</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">En retard</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>