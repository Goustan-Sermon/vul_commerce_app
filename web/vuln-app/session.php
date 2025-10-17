<?php
// ⚠️ Rien ne doit être envoyé avant ces lignes

if (session_status() === PHP_SESSION_NONE) {
    $cookieParams = [
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',  // laisse vide pour localhost
        'secure' => false,
        'httponly' => false
    ];
    session_set_cookie_params($cookieParams);
    session_start();
}

// helper
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
