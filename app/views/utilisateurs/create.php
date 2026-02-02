<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>utilisateurs" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>utilisateurs/store" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe *</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required class="form-control" minlength="6">
                <small class="form-text">Minimum 6 caractères</small>
            </div>
            
            <div class="form-group">
                <label for="role">Rôle *</label>
                <select id="role" name="role" required class="form-control">
                    <option value="">-- Sélectionner --</option>
                    <option value="bibliothecaire">Bibliothécaire</option>
                    <option value="administrateur">Administrateur</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= BASE_URL ?>utilisateurs" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>