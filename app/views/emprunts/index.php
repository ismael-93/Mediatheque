<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>emprunts/create" class="btn btn-primary">+ Nouvel emprunt</a>
        <a href="<?= BASE_URL ?>emprunts/retards" class="btn btn-danger">⚠️ Retards</a>
        <a href="<?= BASE_URL ?>emprunts/historique" class="btn btn-secondary">📜 Historique</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($emprunts)): ?>
            <p class="text-muted">Aucun emprunt en cours.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Adhérent</th>
                        <th>Document</th>
                        <th>Type</th>
                        <th>Date emprunt</th>
                        <th>Date retour prévue</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($emprunt['adherent_prenom'] . ' ' . $emprunt['adherent_nom']) ?></strong><br>
                                <small><?= htmlspecialchars($emprunt['numero_carte']) ?></small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($emprunt['document_titre']) ?></strong><br>
                                <small><?= htmlspecialchars($emprunt['document_auteur'] ?? '') ?></small>
                            </td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($emprunt['libelle_type']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></td>
                            <td>
                                <?php if ($emprunt['jours_restants'] < 0): ?>
                                    <span class="badge badge-danger">En retard (<?= abs($emprunt['jours_restants']) ?> j)</span>
                                <?php elseif ($emprunt['jours_restants'] <= 3): ?>
                                    <span class="badge badge-warning"><?= $emprunt['jours_restants'] ?> jours</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= $emprunt['jours_restants'] ?> jours</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>emprunts/retour/<?= $emprunt['id_emprunt'] ?>" class="btn btn-sm btn-primary">Retour</a>
                                <a href="<?= BASE_URL ?>emprunts/renouveler/<?= $emprunt['id_emprunt'] ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Renouveler cet emprunt ?')">Renouveler</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>