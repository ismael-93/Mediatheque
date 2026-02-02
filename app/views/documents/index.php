<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>documents/search" class="btn btn-secondary">🔍 Rechercher</a>
        <?php if (!Session::isAdherent()): ?>
            <a href="<?= BASE_URL ?>documents/create" class="btn btn-primary">+ Ajouter</a>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($documents)): ?>
            <p class="text-muted">Aucun document enregistré.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Type</th>
                        <th>Année</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($doc['titre']) ?></strong></td>
                            <td><?= htmlspecialchars($doc['auteur'] ?? '-') ?></td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($doc['libelle_type']) ?></span></td>
                            <td><?= $doc['annee_parution'] ?? '-' ?></td>
                            <td>
                                <?php if ($doc['disponible']): ?>
                                    <span class="badge badge-success">Disponible</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Emprunté</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>documents/show/<?= $doc['id_document'] ?>" class="btn btn-sm btn-info">Voir</a>
                                <?php if (!Session::isAdherent()): ?>
                                    <a href="<?= BASE_URL ?>documents/edit/<?= $doc['id_document'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <?php if ($doc['disponible']): ?>
                                        <a href="<?= BASE_URL ?>documents/delete/<?= $doc['id_document'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce document ?')">Supprimer</a>
                                    <?php endif; ?>
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