<aside class="sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="<?= BASE_URL ?>dashboard" class="<?= ($_GET['url'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                🏠 Tableau de bord
            </a>
        </li>
        
        <li class="menu-title">Gestion</li>
        
        <li>
            <a href="<?= BASE_URL ?>documents" class="<?= strpos($_GET['url'] ?? '', 'documents') === 0 ? 'active' : '' ?>">
                📖 Documents
            </a>
        </li>
        
        <li>
            <a href="<?= BASE_URL ?>adherents" class="<?= strpos($_GET['url'] ?? '', 'adherents') === 0 ? 'active' : '' ?>">
                👥 Adhérents
            </a>
        </li>
        
        <li>
            <a href="<?= BASE_URL ?>emprunts" class="<?= strpos($_GET['url'] ?? '', 'emprunts') === 0 ? 'active' : '' ?>">
                📋 Emprunts
            </a>
        </li>
        
        <li>
            <a href="<?= BASE_URL ?>emprunts/retards" class="<?= ($_GET['url'] ?? '') === 'emprunts/retards' ? 'active' : '' ?>">
                ⚠️ Retards
            </a>
        </li>
        
        <li>
            <a href="<?= BASE_URL ?>emprunts/historique" class="<?= ($_GET['url'] ?? '') === 'emprunts/historique' ? 'active' : '' ?>">
                📜 Historique
            </a>
        </li>
        
        <?php if (Session::isAdmin()): ?>
            <li class="menu-title">Administration</li>
            
            <li>
                <a href="<?= BASE_URL ?>types-documents" class="<?= strpos($_GET['url'] ?? '', 'types-documents') === 0 ? 'active' : '' ?>">
                    📁 Types de documents
                </a>
            </li>
            
            <li>
                <a href="<?= BASE_URL ?>utilisateurs" class="<?= strpos($_GET['url'] ?? '', 'utilisateurs') === 0 ? 'active' : '' ?>">
                    🔐 Utilisateurs
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>