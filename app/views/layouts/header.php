<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Médiathèque' ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/dashboard.css">
</head>
<body>
    <?php if (Session::isLoggedIn()): ?>
        <?php require_once VIEWS_PATH . 'layouts/navbar.php'; ?>
    <?php endif; ?>
    
    <div class="container <?= Session::isLoggedIn() ? 'with-sidebar' : '' ?>">
        <?php if (Session::isLoggedIn() && !Session::isAdherent()): ?>
            <?php require_once VIEWS_PATH . 'layouts/sidebar.php'; ?>
        <?php endif; ?>
        
        <main class="main-content">
            <?php
            $flash = Session::getFlash();
            if ($flash):
            ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>