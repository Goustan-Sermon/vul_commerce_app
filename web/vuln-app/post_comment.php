<?php
require 'db.php';

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 1;
$author = isset($_POST['author']) ? $mysqli->real_escape_string($_POST['author']) : 'anon';
$content = isset($_POST['content']) ? $_POST['content'] : '';

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['photo']['tmp_name'];
    $name = basename($_FILES['photo']['name']);
    $dest = __DIR__ . '/uploads/' . uniqid() . '-' . $name;
    move_uploaded_file($tmp, $dest);
    if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'xml') {
        $xmlcontent = file_get_contents($dest);
        $content .= "\n\n[XML UPLOADED]\n" . $xmlcontent;
    }
}

$sql = "INSERT INTO comments (product_id, author, content) VALUES ($product_id, '$author', '".$mysqli->real_escape_string($content)."')";
if (!$mysqli->query($sql)) {
    die("Erreur insert: " . $mysqli->error);
}
header("Location: product.php?id=" . $product_id);
