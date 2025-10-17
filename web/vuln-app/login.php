<?php

require 'db.php';
require 'session.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT id, password, username, role FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $hash, $username, $role);
        if ($stmt->fetch()) {
            if (password_verify($password, $hash)) {
                // successful login
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
                header('Location: profile.php');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        } else {
            $error = "Email ou mot de passe incorrect";
        }
        $stmt->close();
    } else {
        $error = "Erreur BD: " . $mysqli->error;
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Connexion</title></head>
<body>
  <h1>Connexion</h1>
  <?php if(!empty($_GET['registered'])): ?><p style="color:green">Inscription réussie — connecte-toi</p><?php endif; ?>
  <?php if($error): ?><p style="color:red"><?= e($error) ?></p><?php endif; ?>
  <form method="post" action="">
    Email: <input name="email" type="email" required><br>
    Mot de passe: <input name="password" type="password" required><br>
    <button type="submit">Se connecter</button>
  </form>
  <p><a href="register.php">S'inscrire</a></p>
  <a href="/logout.php">Se déconnecter</a>

</body>
</html>
