<?php
/**
 * Classe Router - Gestion des routes
 */

class Router
{
    private array $routes = [];

    /**
     * Ajouter une route GET
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Ajouter une route POST
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Résoudre la route actuelle
     */
    public function resolve(): void
    {
        // Récupérer l'URL demandée
        $path = $_GET['url'] ?? 'dashboard';
        $path = trim($path, '/');
        
        // Méthode HTTP (GET ou POST)
        $method = $_SERVER['REQUEST_METHOD'];

        // Chercher la route
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            $this->callController($route['controller'], $route['method']);
            return;
        }

        // Vérifier les routes avec paramètres (ex: documents/edit/5)
        foreach ($this->routes[$method] ?? [] as $routePath => $route) {
            $pattern = $this->convertToRegex($routePath);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Enlever le match complet
                $this->callController($route['controller'], $route['method'], $matches);
                return;
            }
        }

        // Route non trouvée → 404
        $this->notFound();
    }

    /**
     * Convertir une route en regex
     */
    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([0-9]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Appeler le contrôleur
     */
    private function callController(string $controller, string $method, array $params = []): void
    {
        $controllerFile = CONTROLLERS_PATH . $controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controller)) {
                $instance = new $controller();
                
                if (method_exists($instance, $method)) {
                    call_user_func_array([$instance, $method], $params);
                    return;
                }
            }
        }
        
        $this->notFound();
    }

    /**
     * Page 404
     */
    private function notFound(): void
    {
        http_response_code(404);
        require_once VIEWS_PATH . 'errors/404.php';
        exit;
    }
}