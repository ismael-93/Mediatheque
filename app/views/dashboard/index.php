<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
</div>

<?php if (Session::isAdherent()): ?>
    
    <div class="card">
        <div class="card-header">
            <h2>📋 Mes emprunts en cours (<?= $nbEmpruntsEnCours ?>)</h2>
        </div>
        <div class="card-body">
            <?php if (empty($empruntsEnCours)): ?>
                <p class="text-muted">Vous n'avez aucun emprunt en cours.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Type</th>
                            <th>Date emprunt</th>
                            <th>Date retour prévue</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empruntsEnCours as $emprunt): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($emprunt['document_titre']) ?></strong><br>
                                    <small><?= htmlspecialchars($emprunt['document_auteur'] ?? '') ?></small>
                                </td>
                                <td><?= htmlspecialchars($emprunt['libelle_type']) ?></td>
                                <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($emprunt['date_retour_prevue'])) ?></td>
                                <td>
                                    <?php if ($emprunt['jours_restants'] < 0): ?>
                                        <span class="badge badge-danger">En retard</span>
                                    <?php elseif ($emprunt['jours_restants'] <= 3): ?>
                                        <span class="badge badge-warning">Bientôt</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= $emprunt['jours_restants'] ?> jours</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">📖</div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['totalDocuments'] ?></span>
                <span class="stat-label">Documents</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['documentsDisponibles'] ?></span>
                <span class="stat-label">Disponibles</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['totalAdherents'] ?></span>
                <span class="stat-label">Adhérents</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['empruntsEnCours'] ?></span>
                <span class="stat-label">Emprunts</span>
            </div>
        </div>
        
        <div class="stat-card stat-card-danger">
            <div class="stat-icon">⚠️</div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['empruntsRetard'] ?></span>
                <span class="stat-label">Retards</span>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <h2>⚠️ Emprunts en retard</h2>
                <a href="<?= BASE_URL ?>emprunts/retards" class="btn btn-sm">Voir tout</a>
            </div>
            <div class="card-body">
                <?php if (empty($empruntsRetard)): ?>
                    <p class="text-success">Aucun emprunt en retard 🎉</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Adhérent</th>
                                <th>Document</th>
                                <th>Retard</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($empruntsRetard, 0, 5) as $emprunt): ?>
                                <tr>
                                    <td><?= htmlspecialchars($emprunt['adherent_prenom'] . ' ' . $emprunt['adherent_nom']) ?></td>
                                    <td><?= htmlspecialchars($emprunt['document_titre']) ?></td>
                                    <td><span class="badge badge-danger"><?= $emprunt['jours_retard'] ?> jours</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>📖 Derniers documents</h2>
                <a href="<?= BASE_URL ?>documents" class="btn btn-sm">Voir tout</a>
            </div>
            <div class="card-body">
                <?php if (empty($derniersDocuments)): ?>
                    <p class="text-muted">Aucun document.</p>
                <?php else: ?>
                    <ul class="list-simple">
                        <?php foreach ($derniersDocuments as $doc): ?>
                            <li>
                                <strong><?= htmlspecialchars($doc['titre']) ?></strong>
                                <span class="badge badge-info"><?= htmlspecialchars($doc['libelle_type']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>