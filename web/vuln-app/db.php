<?php
// db.php
$host = getenv('DB_HOST') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: '';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}
?>
