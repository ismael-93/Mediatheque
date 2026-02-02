<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body class="error-page">
    <div class="error-container">
        <div class="error-box">
            <h1>404</h1>
            <h2>Page non trouvée</h2>
            <p>La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="<?= BASE_URL ?>dashboard" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>