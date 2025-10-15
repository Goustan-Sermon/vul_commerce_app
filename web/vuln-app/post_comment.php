<?php
require 'db.php'; // fournit $mysqli

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 1;
$author = isset($_POST['author']) ? trim($_POST['author']) : 'anon';
$content = isset($_POST['content']) ? $_POST['content'] : '';

// dossier uploads
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    @chown($uploadDir, 'www-data');
    @chgrp($uploadDir, 'www-data');
}

$uploadedFilename = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['photo'];
    if ($f['error'] === UPLOAD_ERR_OK) {
        $safeName = preg_replace('/[^\w\-.]+/u', '_', basename($f['name']));
        $uniq = bin2hex(random_bytes(8));
        $targetName = $uniq . '-' . $safeName;
        $targetPath = $uploadDir . '/' . $targetName;
        if (move_uploaded_file($f['tmp_name'], $targetPath)) {
            $uploadedFilename = $targetName;
        } else {
            error_log("UPLOAD ERROR: move_uploaded_file failed");
        }
    }
}

// insertion en base
$authorEsc = $mysqli->real_escape_string($author);
$contentEsc = $mysqli->real_escape_string($content);

if ($uploadedFilename !== null) {
    $fileEsc = $mysqli->real_escape_string($uploadedFilename);
    $sql = "INSERT INTO comments (product_id, author, content, image) VALUES ($product_id, '$authorEsc', '$contentEsc', '$fileEsc')";
} else {
    $sql = "INSERT INTO comments (product_id, author, content) VALUES ($product_id, '$authorEsc', '$contentEsc')";
}

if (!$mysqli->query($sql)) {
    error_log("DB INSERT ERROR: " . $mysqli->error);
}

// redirection
header("Location: product.php?id=" . $product_id);
exit;
