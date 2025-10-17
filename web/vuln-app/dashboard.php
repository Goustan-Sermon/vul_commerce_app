<?php

require 'db.php';
require 'session.php';

// accès restreint
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'Utilisateur';
$role = $_SESSION['role'] ?? 'user';
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
  <h1>Dashboard</h1>
  <p>Bienvenue <?= e($username) ?> — rôle: <?= e($role) ?></p>

  <?php if ($role === 'admin'): ?>
    <p><a href="admin_dashboard.php">Aller au dashboard Admin</a></p>
  <?php else: ?>
    <p>Zone utilisateur standard.</p>
  <?php endif; ?>

  <p><a href="logout.php">Se déconnecter</a></p>
</body></html>
