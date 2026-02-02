<?php
/**
 * Générateur de hash pour mots de passe
 */

$passwords = [
    'admin123',
    'biblio123',
    'adherent123',
    '1234'
];

echo "<h1>🔐 Générateur de Hash</h1>";
echo "<style>
    body { font-family: Arial; padding: 20px; }
    .hash-box { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .hash { background: white; padding: 10px; border: 1px solid #ddd; word-break: break-all; }
</style>";

foreach ($passwords as $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<div class='hash-box'>";
    echo "<h3>Mot de passe : <strong>$password</strong></h3>";
    echo "<div class='hash'>$hash</div>";
    
    // Test de vérification
    if (password_verify($password, $hash)) {
        echo "<p style='color: green;'>✅ Hash vérifié avec succès</p>";
    } else {
        echo "<p style='color: red;'>❌ Erreur de vérification</p>";
    }
    echo "</div>";
}
?>