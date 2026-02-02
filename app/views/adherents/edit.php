<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="<?= BASE_URL ?>adherents" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= BASE_URL ?>adherents/update/<?= $adherent['id_adherent'] ?>" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required class="form-control" value="<?= htmlspecialchars($adherent['nom']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required class="form-control" value="<?= htmlspecialchars($adherent['prenom']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required class="form-control" value="<?= htmlspecialchars($adherent['email']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" class="form-control" value="<?= htmlspecialchars($adherent['telephone'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" class="form-control" rows="3"><?= htmlspecialchars($adherent['adresse'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Nouveau mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" minlength="6">
                <small class="form-text">Laisser vide pour ne pas modifier</small>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="actif" value="1" <?= $adherent['actif'] ? 'checked' : '' ?>>
                    Compte actif
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= BASE_URL ?>adherents" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>