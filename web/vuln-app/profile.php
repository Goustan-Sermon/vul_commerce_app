<?php

require 'session.php';


// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mon profil</title>
</head>
<body>
  <h1>Bienvenue sur ton profil 👋</h1>
  <p><strong>ID :</strong> <?= htmlspecialchars($_SESSION['user_id']) ?></p>
  <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
  <p><strong>Rôle :</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>

  <?php if ($_SESSION['role'] === 'admin'): ?>
    <p style="color:green;">✅ Tu es admin — <a href="dashboard.php">accéder au dashboard</a></p>
  <?php else: ?>
    <p style="color:blue;">👤 Tu es un utilisateur normal</p>
  <?php endif; ?>

  <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>
