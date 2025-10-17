<?php

require 'db.php';
require 'session.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide";
    } elseif (strlen($password) < 8) {
        $error = "Mot de passe trop court (min 8 caractères)";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (email, password, username) VALUES (?, ?, ?)");
        if (!$stmt) {
            $error = "Erreur BD prepare: " . $mysqli->error;
        } else {
            $stmt->bind_param('sss', $email, $hash, $username);
            if ($stmt->execute()) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                // duplicate email ou autre erreur
                $error = "Impossible d'enregistrer (email peut-être déjà utilisé)";
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Inscription</title></head>
<body>
  <h1>Inscription</h1>
  <?php if($error): ?><p style="color:red"><?= e($error) ?></p><?php endif; ?>
  <form method="post" action="">
    Email: <input name="email" type="email" required><br>
    Username: <input name="username" type="text"><br>
    Mot de passe: <input name="password" type="password" required><br>
    <button type="submit">S'inscrire</button>
  </form>
  <p><a href="login.php">Se connecter</a></p>
</body>
</html>
