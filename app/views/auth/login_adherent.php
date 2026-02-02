<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Adhérent - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>📚 <?= APP_NAME ?></h1>
                <p>Espace Adhérent</p>
            </div>
            
            <?php
            $flash = Session::getFlash();
            if ($flash):
            ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>login-adherent" method="POST" class="login-form">
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
                <p>Vous êtes bibliothécaire ?</p>
                <a href="<?= BASE_URL ?>login" class="btn btn-secondary btn-block">Espace Personnel</a>
            </div>
        </div>
    </div>
</body>
</html>