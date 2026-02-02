<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>📚 <?= APP_NAME ?></h1>
                <p>Espace Personnel</p>
            </div>
            
            <?php
            $flash = Session::getFlash();
            if ($flash):
            ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>login" method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="votre@email.com">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>
            
            <div class="login-footer">
                <p>Vous êtes adhérent ?</p>
                <a href="<?= BASE_URL ?>login-adherent" class="btn btn-secondary btn-block">Espace Adhérent</a>
            </div>
        </div>
    </div>
</body>
</html>