<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>emprunts" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($emprunts)): ?>
            <p class="text-muted">Aucun emprunt enregistré.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Adhérent</th>
                        <th>Document</th>
                        <th>Type</th>
                        <th>Date emprunt</th>
                        <th>Date retour prévue</th>
                        <th>Date retour effective</th>
                        <th>Statut</th>
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
                                <?php if ($emprunt['date_retour_effective']): ?>
                                    <?= date('d/m/Y', strtotime($emprunt['date_retour_effective'])) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
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