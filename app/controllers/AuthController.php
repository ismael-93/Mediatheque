<?php
/**
 * AuthController - Gestion de l'authentification
 */

require_once MODELS_PATH . 'Utilisateur.php';
require_once MODELS_PATH . 'Adherent.php';

class AuthController extends Controller
{
    private Utilisateur $utilisateurModel;
    private Adherent $adherentModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
        $this->adherentModel = new Adherent();
    }

    /**
     * Afficher le formulaire de connexion (Bibliothécaire/Admin)
     */
    public function loginForm(): void
    {
        // Si déjà connecté, rediriger
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->view('auth/login');
    }

    /**
     * Traiter la connexion (Bibliothécaire/Admin)
     */
    // DANS app/controllers/AuthController.php

public function login(): void
{
    // 1. On récupère les données brutes (sans nettoyage pour voir la vérité)
    $email_recu = $_POST['email'] ?? 'RIEN';
    $pass_recu  = $_POST['password'] ?? 'RIEN';

    echo "<div style='background:white; padding:20px; color:black; font-family:sans-serif;'>";
    echo "<h1>🕵️ DIAGNOSTIC LOGIN</h1>";
    
    // 2. Vérification des données reçues
    echo "<h3>1. Données reçues du formulaire :</h3>";
    echo "Email : [" . $email_recu . "] <br>"; 
    echo "Mot de passe : [" . $pass_recu . "] <br>";

    // 3. Vérification de ce que trouve le Modèle
    echo "<h3>2. Recherche dans la base de données :</h3>";
    // On appelle directement le modèle sans passer par la fonction authenticate pour voir
    $user = $this->utilisateurModel->findByEmail($email_recu);

    if (!$user) {
        echo "<strong style='color:red'>❌ ERREUR : L'utilisateur n'est pas trouvé par findByEmail.</strong><br>";
        echo "Vérifie qu'il n'y a pas d'espace avant/après l'email dans ton champ de saisie.";
    } else {
        echo "<strong style='color:green'>✅ Utilisateur trouvé !</strong><br>";
        echo "Hash en base : " . $user['mot_de_passe'] . "<br>";
        
        // 4. Test du mot de passe en direct
        echo "<h3>3. Vérification du mot de passe :</h3>";
        if (password_verify($pass_recu, $user['mot_de_passe'])) {
            echo "<h2 style='color:green'>✅ LE MOT DE PASSE EST BON !</h2>";
            echo "Si tu vois ça, c'est que ton code original a un bug, mais que la connexion marche.";
            
            // On connecte manuellement pour te débloquer
            Session::set('user_id', $user['id_utilisateur']);
            Session::set('user_prenom', $user['prenom']);
            Session::set('role', $user['role']);
            echo "<br><a href='index.php?action=dashboard' style='font-size:20px; font-weight:bold;'>👉 CLIQUE ICI POUR ACCÉDER AU DASHBOARD</a>";
            die();
        } else {
            echo "<h2 style='color:red'>❌ LE MOT DE PASSE EST REFUSÉ</h2>";
            echo "password_verify a dit NON. <br>";
            echo "Vérifie majuscules/minuscules.";
        }
    }
    echo "</div>";
    die(); // On arrête tout ici pour lire le résultat
}

    /**
     * Afficher le formulaire de connexion (Adhérent)
     */
    public function loginAdherentForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->view('auth/login_adherent');
    }

    /**
     * Traiter la connexion (Adhérent)
     */
    public function loginAdherent(): void
    {
        if (!$this->isPost()) {
            $this->redirect('login-adherent');
        }

        $email = $this->post('email');
        $password = $this->post('password');

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('login-adherent');
        }

        $adherent = $this->adherentModel->authenticate($email, $password);

        if ($adherent) {
            // Vérifier si l'abonnement n'est pas expiré
            if (strtotime($adherent['date_expiration']) < time()) {
                Session::setFlash('error', 'Votre abonnement a expiré. Contactez la médiathèque.');
                $this->redirect('login-adherent');
            }

            Session::set('adherent_id', $adherent['id_adherent']);
            Session::set('adherent_nom', $adherent['nom']);
            Session::set('adherent_prenom', $adherent['prenom']);
            Session::set('adherent_email', $adherent['email']);
            Session::set('role', 'adherent');

            Session::setFlash('success', 'Bienvenue ' . $adherent['prenom'] . ' !');
            $this->redirect('dashboard');
        } else {
            Session::setFlash('error', 'Email ou mot de passe incorrect.');
            $this->redirect('login-adherent');
        }
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        Session::destroy();
        Session::start();
        Session::setFlash('success', 'Vous avez été déconnecté.');
        $this->redirect('login');
    }
}