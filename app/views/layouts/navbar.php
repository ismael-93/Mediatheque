<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= BASE_URL ?>dashboard">📚 <?= APP_NAME ?></a>
    </div>
    
    <div class="navbar-menu">
        <?php if (Session::isAdherent()): ?>
            <span class="navbar-user">
                👤 <?= Session::get('adherent_prenom') ?> <?= Session::get('adherent_nom') ?>
                <span class="badge badge-info">Adhérent</span>
            </span>
        <?php else: ?>
            <span class="navbar-user">
                👤 <?= Session::get('user_prenom') ?> <?= Session::get('user_nom') ?>
                <span class="badge badge-<?= Session::isAdmin() ? 'danger' : 'warning' ?>">
                    <?= Session::isAdmin() ? 'Admin' : 'Bibliothécaire' ?>
                </span>
            </span>
        <?php endif; ?>
        
        <a href="<?= BASE_URL ?>logout" class="btn btn-logout">Déconnexion</a>
    </div>
</nav>