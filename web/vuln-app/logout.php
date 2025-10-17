<?php
require 'session.php';

// 1️⃣ Vider toutes les variables de session
$_SESSION = [];

// 2️⃣ Supprimer le cookie de session côté navigateur
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000, 
        $params['path'], 
        $params['domain'], 
        $params['secure'], 
        $params['httponly']
    );
}

// 3️⃣ Détruire la session côté serveur
session_destroy();

// 4️⃣ Redirection vers la page de connexion
header('Location: login.php');
exit;
