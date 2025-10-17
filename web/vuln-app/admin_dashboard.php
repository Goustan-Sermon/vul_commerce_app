<?php

require 'db.php';
require 'session.php';

// Vérifier connexion
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Vérifier rôle admin
if ( ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo "Accès refusé — réservé aux administrateurs.";
    exit;
}

// action admin simulée (POST sécurisé par CSRF)
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        die('CSRF token invalide');
    }
    // simulation d'action admin
    $message = "Action admin exécutée (simulation)";
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Dashboard</title></head>
<body>
  <h1>Admin Dashboard</h1>
  <p>Bienvenue admin <?= e($_SESSION['username'] ?? '') ?></p>

  <?php if($message): ?><p style="color:green"><?= e($message) ?></p><?php endif; ?>

  <form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
    <button type="submit">Exécuter action admin (simulée)</button>
  </form>

  <p><a href="dashboard.php">Retour</a> — <a href="logout.php">Se déconnecter</a></p>
</body></html>
