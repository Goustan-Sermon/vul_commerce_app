<?php

if (!isset($_FILES['xmlfile']) || $_FILES['xmlfile']['error'] !== UPLOAD_ERR_OK) {
    echo "Upload XML requis"; exit;
}
$tmp = $_FILES['xmlfile']['tmp_name'];
$xml = file_get_contents($tmp);

libxml_disable_entity_loader(false);
$doc = new DOMDocument();
$doc->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
echo "Parsed XML (raw):\n";
echo htmlspecialchars($doc->saveXML());
?>
