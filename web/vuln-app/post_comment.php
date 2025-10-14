<?php
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'vulnshop';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) die("DB error: ".$mysqli->connect_error);

$product_id = $_POST['product_id'] ?? '1';
$author = $_POST['author'] ?? 'anon';
$content = $_POST['content'] ?? '';

// Insertion vulnérable (concaténation directe) — stored XSS possible
$sql = "INSERT INTO comments (product_id, author, content) VALUES ($product_id, '".$mysqli->real_escape_string($author)."', '".$mysqli->real_escape_string($content)."')";
if (!$mysqli->query($sql)) {
    echo "Erreur insert: " . $mysqli->error;
} else {
    header("Location: product.php?id=".$product_id);
}
