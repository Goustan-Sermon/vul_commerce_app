<?php

require 'db.php';

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 1;
$author = isset($_POST['author']) ? trim($_POST['author']) : 'anon';
$content = isset($_POST['content']) ? $_POST['content'] : '';

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
        $tmp = $f['tmp_name'];

        // detection mime réelle
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        $allowed = ['image/png', 'image/jpeg', 'image/svg+xml'];
        if (!in_array($mime, $allowed, true)) {
            error_log("Upload rejected: mime={$mime}");
            // on continue sans image
        } else {
            $originalName = basename($f['name']);
            $safeName = preg_replace('/[^\w\-.]+/u', '_', $originalName);
            if ($safeName === '') $safeName = 'file';
            $uniq = bin2hex(random_bytes(8));
            $targetName = $uniq . '-' . $safeName;
            $targetPath = $uploadDir . '/' . $targetName;

            if (move_uploaded_file($tmp, $targetPath)) {
                @chown($targetPath, 'www-data');
                @chgrp($targetPath, 'www-data');
                @chmod($targetPath, 0644);
                $uploadedFilename = $targetName;

                // === VULN: si SVG (XML), parser le fichier et EXPANDRE les entités externes ===
                if ($mime === 'image/svg+xml') {
                    // lire le contenu
                    $xml = file_get_contents($targetPath);
                    libxml_use_internal_errors(true);

                    $doc = new DOMDocument();
                    $loaded = $doc->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);

                    // collecter les erreurs si besoin et logger, sans les afficher
                    if (!$loaded) {
                        $errs = libxml_get_errors();
                        foreach ($errs as $err) {
                            error_log("libxml error: " . trim($err->message));
                        }
                        libxml_clear_errors();
                    } else {
                        // récupère le XML après expansion d'entités
                        $parsedXml = $doc->saveXML();
                        // concatène le XML parsé au commentaire (pour que le flag apparaisse ensuite)
                        $content .= "\n\n[PARSED_SVG]\n" . $parsedXml;
                    }

                    // remettre l'état des erreurs libxml par défaut (optionnel)
                    libxml_use_internal_errors(false);
                }
            } else {
                error_log("UPLOAD ERROR: move_uploaded_file failed for {$tmp} -> {$targetPath}");
            }
        }
    } else {
        error_log("UPLOAD ERROR CODE: " . $f['error']);
    }
}

// Préparer et insérer en base
$authorEsc = $mysqli->real_escape_string($author);
$contentEsc = $mysqli->real_escape_string($content);

if ($uploadedFilename !== null) {
    $fileEsc = $mysqli->real_escape_string($uploadedFilename);
    $sql = "INSERT INTO comments (product_id, author, content, image) VALUES ($product_id, '$authorEsc', '$contentEsc', '$fileEsc')";
} else {
    $sql = "INSERT INTO comments (product_id, author, content) VALUES ($product_id, '$authorEsc', '$contentEsc')";
}

if (!$mysqli->query($sql)) {
    error_log("DB INSERT ERROR: " . $mysqli->error . " -- SQL: " . $sql);
}

header("Location: product.php?id=" . $product_id);
exit;

