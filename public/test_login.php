<?php
// test_login.php

// 1. Connexion (Vérifie tes infos)
$host = 'localhost';
$db   = 'mediatheque';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    echo "<h1>🕵️‍♂️ Diagnostic de Connexion</h1>";

    // A. Vérifier si l'utilisateur existe
    $email = 'admin@test.com';
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $userFound = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userFound) {
        echo "<h3 style='color:red'>❌ ERREUR : L'email '$email' n'existe pas dans la table 'utilisateur'.</h3>";
        // On le crée pour toi
        echo "👉 Tentative de création de l'admin...<br>";
        $mdp = password_hash('1234', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role, actif) VALUES ('Admin', 'Super', '$email', '$mdp', 'administrateur', 1)");
        echo "<strong style='color:green'>✅ Admin créé ! Rafraîchis cette page.</strong>";
        exit;
    }

    echo "✅ Utilisateur trouvé (ID: " . $userFound['id_utilisateur'] . ")<br>";

    // B. Vérifier l'état Actif
    if ($userFound['actif'] == 0) {
        echo "<h3 style='color:red'>❌ ERREUR : Le compte est désactivé (actif = 0).</h3>";
        $pdo->exec("UPDATE utilisateur SET actif = 1 WHERE email = '$email'");
        echo "✅ Compte réactivé automatiquement.<br>";
    } else {
        echo "✅ Compte actif.<br>";
    }

    // C. Vérifier le mot de passe
    $passwordTape = "1234";
    echo "🔹 Test du mot de passe : <strong>$passwordTape</strong><br>";
    echo "🔹 Hash en base : <small>" . $userFound['mot_de_passe'] . "</small><br>";
    echo "🔹 Longueur du hash : " . strlen($userFound['mot_de_passe']) . " caractères (doit être > 50)<br>";

    if (password_verify($passwordTape, $userFound['mot_de_passe'])) {
        echo "<h2 style='color:green'>🎉 SUCCÈS : Tout fonctionne !</h2>";
        echo "Le problème venait sûrement de ton fichier <code>login.php</code> (l'attribut action).<br>";
        echo "👉 <a href='index.php?action=login'>Retourne te connecter maintenant</a>";
    } else {
        echo "<h2 style='color:red'>❌ ÉCHEC : Le mot de passe est incorrect.</h2>";
        echo "Le hash dans la base ne correspond pas à '1234'.<br>";
        
        // Correction automatique
        $newHash = password_hash('1234', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?");
        $stmt->execute([$newHash, $email]);
        echo "<br><strong>✅ J'ai réinitialisé le mot de passe à '1234'. Rafraîchis cette page pour vérifier (ça devrait passer au vert).</strong>";
    }

} catch (Exception $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>