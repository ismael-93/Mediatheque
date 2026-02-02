<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>documents" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>documents/search" method="GET" class="search-form">
            <div class="form-row">
                <div class="form-group flex-grow">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher par titre, auteur ou code-barre..." value="<?= htmlspecialchars($searchTerm ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <select name="type" class="form-control">
                        <option value="">Tous les types</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= $type['id_type_document'] ?>" <?= ($selectedType ?? '') == $type['id_type_document'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['libelle_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($searchTerm)): ?>
    <div class="card">
        <div class="card-header">
            <h2>Résultats (<?= count($documents) ?>)</h2>
        </div>
        <div class="card-body">
            <?php if (empty($documents)): ?>
                <p class="text-muted">Aucun document trouvé.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Type</th>
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
                                <td>
                                    <?php if ($doc['disponible']): ?>
                                        <span class="badge badge-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Emprunté</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>documents/show/<?= $doc['id_document'] ?>" class="btn btn-sm btn-info">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>