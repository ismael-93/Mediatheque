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
            <p class="text-success">🎉 Aucun emprunt en retard !</p>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>⚠️ <?= count($emprunts) ?> emprunt(s) en retard</strong>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Adhérent</th>
                        <th>Contact</th>
                        <th>Document</th>
                        <th>Date emprunt</th>
                        <th>Date retour prévue</th>
                        <th>Retard</th>
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
                                <small>
                                    📧 <?= htmlspecialchars($emprunt['adherent_email']) ?><br>
                                    <?php if (!empty($emprunt['adherent_telephone'])): ?>
                                        📞 <?= htmlspecialchars($emprunt['adherent_telephone']) ?>
                                    <?php endif; ?>
                                </small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($emprunt['document_titre']) ?></strong><br>
                                <small><?= htmlspecialchars($emprunt['document_auteur'] ?? '') ?></small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></td>
                            <td>
                                <span class="badge badge-danger"><?= $emprunt['jours_retard'] ?> jours</span>
                            </td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>emprunts/retour/<?= $emprunt['id_emprunt'] ?>" class="btn btn-sm btn-primary">Retour</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>