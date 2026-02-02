<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>utilisateurs/create" class="btn btn-primary">+ Ajouter</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($utilisateurs)): ?>
            <p class="text-muted">Aucun utilisateur enregistré.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date création</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['prenom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <?php if ($user['role'] === 'administrateur'): ?>
                                    <span class="badge badge-danger">Administrateur</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Bibliothécaire</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                            <td>
                                <?php if ($user['actif']): ?>
                                    <span class="badge badge-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>utilisateurs/edit/<?= $user['id_utilisateur'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <?php if ($user['id_utilisateur'] != Session::get('user_id')): ?>
                                    <a href="<?= BASE_URL ?>utilisateurs/delete/<?= $user['id_utilisateur'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
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