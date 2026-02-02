<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>utilisateurs" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>utilisateurs/update/<?= $utilisateur['id_utilisateur'] ?>" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required class="form-control" value="<?= htmlspecialchars($utilisateur['nom']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required class="form-control" value="<?= htmlspecialchars($utilisateur['prenom']) ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required class="form-control" value="<?= htmlspecialchars($utilisateur['email']) ?>">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Nouveau mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" minlength="6">
                <small class="form-text">Laisser vide pour ne pas modifier</small>
            </div>
            
            <div class="form-group">
                <label for="role">Rôle *</label>
                <select id="role" name="role" required class="form-control">
                    <option value="bibliothecaire" <?= $utilisateur['role'] === 'bibliothecaire' ? 'selected' : '' ?>>Bibliothécaire</option>
                    <option value="administrateur" <?= $utilisateur['role'] === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="actif" value="1" <?= $utilisateur['actif'] ? 'checked' : '' ?>>
                    Compte actif
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= BASE_URL ?>utilisateurs" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>