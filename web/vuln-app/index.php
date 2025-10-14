<?php
$servername = getenv("DB_HOST") ?: 'db';
$username = getenv("DB_USER") ?: 'root';
$password = getenv("DB_PASSWORD") ?: '';
$dbname   = getenv("DB_NAME") ?: 'vulnshop';

if (!extension_loaded('mysqli')) {
    die("Extension mysqli manquante. Vérifie que l'image PHP a bien mysqli installé.\n");
}

$mysqli = @new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    echo "<h2>Connexion MySQL impossible</h2>";
    echo "<p>Erreur ({$mysqli->connect_errno}): " . htmlspecialchars($mysqli->connect_error) . "</p>";
    echo "<p>Hôte: " . htmlspecialchars($servername) . " — Utilisateur: " . htmlspecialchars($username) . " — BDD: " . htmlspecialchars($dbname) . "</p>";
    exit;
}

echo "<h1>Bienvenue sur le site vulnérable !</h1>";
echo "<p>Connexion MySQL réussie 🎉</p>";

$mysqli->close();
?>
