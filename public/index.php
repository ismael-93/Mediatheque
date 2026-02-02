<?php
/**
 * Point d'entrée de l'application
 * Toutes les requêtes passent par ici
 */

// Charger la configuration
require_once '../config/config.php';
require_once '../config/database.php';

// Charger les classes core
require_once CORE_PATH . 'Database.php';
require_once CORE_PATH . 'Session.php';
require_once CORE_PATH . 'Controller.php';
require_once CORE_PATH . 'Router.php';

// Démarrer la session
Session::start();

// Créer le routeur
$router = new Router();

// ============================================================
// DÉFINITION DES ROUTES
// ============================================================

// --- Auth ---
$router->get('login', 'AuthController', 'loginForm');
$router->post('login', 'AuthController', 'login');
$router->get('logout', 'AuthController', 'logout');
$router->get('login-adherent', 'AuthController', 'loginAdherentForm');
$router->post('login-adherent', 'AuthController', 'loginAdherent');

// --- Dashboard ---
$router->get('', 'DashboardController', 'index');
$router->get('dashboard', 'DashboardController', 'index');

// --- Documents ---
$router->get('documents', 'DocumentController', 'index');
$router->get('documents/create', 'DocumentController', 'create');
$router->post('documents/store', 'DocumentController', 'store');
$router->get('documents/show/{id}', 'DocumentController', 'show');
$router->get('documents/edit/{id}', 'DocumentController', 'edit');
$router->post('documents/update/{id}', 'DocumentController', 'update');
$router->get('documents/delete/{id}', 'DocumentController', 'delete');
$router->get('documents/search', 'DocumentController', 'search');

// --- Adhérents ---
$router->get('adherents', 'AdherentController', 'index');
$router->get('adherents/create', 'AdherentController', 'create');
$router->post('adherents/store', 'AdherentController', 'store');
$router->get('adherents/show/{id}', 'AdherentController', 'show');
$router->get('adherents/edit/{id}', 'AdherentController', 'edit');
$router->post('adherents/update/{id}', 'AdherentController', 'update');
$router->get('adherents/delete/{id}', 'AdherentController', 'delete');

// --- Emprunts ---
$router->get('emprunts', 'EmpruntController', 'index');
$router->get('emprunts/create', 'EmpruntController', 'create');
$router->post('emprunts/store', 'EmpruntController', 'store');
$router->get('emprunts/retour/{id}', 'EmpruntController', 'retour');
$router->post('emprunts/retourner/{id}', 'EmpruntController', 'retourner');
$router->get('emprunts/historique', 'EmpruntController', 'historique');
$router->get('emprunts/retards', 'EmpruntController', 'retards');

// --- Types Documents ---
$router->get('types-documents', 'TypeDocumentController', 'index');
$router->get('types-documents/create', 'TypeDocumentController', 'create');
$router->post('types-documents/store', 'TypeDocumentController', 'store');
$router->get('types-documents/edit/{id}', 'TypeDocumentController', 'edit');
$router->post('types-documents/update/{id}', 'TypeDocumentController', 'update');
$router->get('types-documents/delete/{id}', 'TypeDocumentController', 'delete');

// --- Utilisateurs (Admin) ---
$router->get('utilisateurs', 'UtilisateurController', 'index');
$router->get('utilisateurs/create', 'UtilisateurController', 'create');
$router->post('utilisateurs/store', 'UtilisateurController', 'store');
$router->get('utilisateurs/edit/{id}', 'UtilisateurController', 'edit');
$router->post('utilisateurs/update/{id}', 'UtilisateurController', 'update');
$router->get('utilisateurs/delete/{id}', 'UtilisateurController', 'delete');

// ============================================================
// RÉSOUDRE LA ROUTE
// ============================================================

$router->resolve();