<?php
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'vulnshop';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) die("DB error: ".$mysqli->connect_error);

// Récupère id directement depuis GET — vulnérable
$id = isset($_GET['id']) ? $_GET['id'] : '1';

// Requête construite par concaténation (SQLi)
$sql = "SELECT * FROM products WHERE id = $id";
$res = $mysqli->query($sql);
if (!$res) { echo "Erreur SQL: " . $mysqli->error; exit; }

$product = $res->fetch_assoc();
if (!$product) { echo "Produit introuvable"; exit; }

echo "<h1>".htmlspecialchars($product['name'])."</h1>";
echo "<p>".htmlspecialchars($product['description'])."</p>";
echo "<p>Prix: ".htmlspecialchars($product['price'])."€</p>";

// Afficher les commentaires (contenu non échappé pour stored XSS demo)
$coms = $mysqli->query("SELECT * FROM comments WHERE product_id = ".$product['id']);
echo "<h2>Commentaires</h2><ul>";
while($c = $coms->fetch_assoc()){
    echo "<li><strong>".htmlspecialchars($c['author'])."</strong>: ".$c['content']."</li>";
}
echo "</ul>";

// Formulaire pour poster un commentaire
?>
<form method="post" action="post_comment.php">
  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
  Auteur: <input name="author"><br>
  Commentaire:<br>
  <textarea name="content" cols="40" rows="4"></textarea><br>
  <button type="submit">Envoyer</button>
</form>
