<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>adherents/create" class="btn btn-primary">+ Ajouter</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($adherents)): ?>
            <p class="text-muted">Aucun adhérent enregistré.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Carte</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Expiration</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adherents as $adherent): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($adherent['numero_carte'] ?? '-') ?></strong></td>
                            <td><?= htmlspecialchars($adherent['nom']) ?></td>
                            <td><?= htmlspecialchars($adherent['prenom']) ?></td>
                            <td><?= htmlspecialchars($adherent['email']) ?></td>
                            <td><?= htmlspecialchars($adherent['telephone'] ?? '-') ?></td>
                            <td>
                                <?php 
                                $expiration = strtotime($adherent['date_expiration']);
                                $now = time();
                                if ($expiration < $now): ?>
                                    <span class="badge badge-danger"><?= date('d/m/Y', $expiration) ?></span>
                                <?php elseif ($expiration < strtotime('+30 days')): ?>
                                    <span class="badge badge-warning"><?= date('d/m/Y', $expiration) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= date('d/m/Y', $expiration) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($adherent['actif']): ?>
                                    <span class="badge badge-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>adherents/show/<?= $adherent['id_adherent'] ?>" class="btn btn-sm btn-info">Voir</a>
                                <a href="<?= BASE_URL ?>adherents/edit/<?= $adherent['id_adherent'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="<?= BASE_URL ?>adherents/delete/<?= $adherent['id_adherent'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet adhérent ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>