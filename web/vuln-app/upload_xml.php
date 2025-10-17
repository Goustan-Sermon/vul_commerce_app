<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<form method="post" enctype="multipart/form-data">
            Upload XML: <input type="file" name="xmlfile">
            <button>Upload</button>
          </form>';
    exit;
}

if (!isset($_FILES['xmlfile']) || $_FILES['xmlfile']['error'] !== UPLOAD_ERR_OK) {
    die("Upload failed");
}

$xml = file_get_contents($_FILES['xmlfile']['tmp_name']);

libxml_disable_entity_loader(false);
$doc = new DOMDocument();

$doc->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);

header("Content-Type: text/plain; charset=utf-8");
echo $doc->saveXML();
