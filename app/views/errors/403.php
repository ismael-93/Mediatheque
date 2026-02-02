<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body class="error-page">
    <div class="error-container">
        <div class="error-box">
            <h1>403</h1>
            <h2>Accès refusé</h2>
            <p>Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
            <a href="<?= BASE_URL ?>dashboard" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>