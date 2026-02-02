<?php
/**
 * Classe Controller - Classe parente des contrôleurs
 */

class Controller
{
    /**
     * Charger une vue
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        
        $viewPath = VIEWS_PATH . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Vue non trouvée : $viewPath");
        }
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect(string $url): void
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    /**
     * Vérifier si la requête est POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Obtenir une donnée POST nettoyée
     */
    protected function post(string $key, $default = null)
    {
        if (isset($_POST[$key])) {
            return htmlspecialchars(trim($_POST[$key]), ENT_QUOTES, 'UTF-8');
        }
        return $default;
    }

    /**
     * Obtenir une donnée GET nettoyée
     */
    protected function get(string $key, $default = null)
    {
        if (isset($_GET[$key])) {
            return htmlspecialchars(trim($_GET[$key]), ENT_QUOTES, 'UTF-8');
        }
        return $default;
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function requireLogin(): void
    {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Vous devez être connecté.');
            $this->redirect('login');
        }
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Accès réservé aux administrateurs.');
            $this->redirect('dashboard');
        }
    }

    /**
     * Vérifier si l'utilisateur est bibliothécaire ou admin
     */
    protected function requireStaff(): void
    {
        $this->requireLogin();
        if (!Session::isAdmin() && !Session::isBibliothecaire()) {
            Session::setFlash('error', 'Accès réservé au personnel.');
            $this->redirect('dashboard');
        }
    }
}