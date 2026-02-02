<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>types-documents/create" class="btn btn-primary">+ Ajouter</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($types)): ?>
            <p class="text-muted">Aucun type de document enregistré.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Durée d'emprunt</th>
                        <th>Renouvellements max</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($types as $type): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($type['libelle_type']) ?></strong></td>
                            <td><?= $type['duree_emprunt_jours'] ?> jours</td>
                            <td><?= $type['nb_renouvellements_max'] ?></td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>types-documents/edit/<?= $type['id_type_document'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="<?= BASE_URL ?>types-documents/delete/<?= $type['id_type_document'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce type ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>