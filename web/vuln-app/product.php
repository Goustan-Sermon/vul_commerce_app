<?php
require 'db.php';
$id = isset($_GET['id']) ? $_GET['id'] : '1';

$productRes = $mysqli->query("SELECT * FROM products WHERE id = $id");
$product = $productRes->fetch_assoc();

$comsRes = $mysqli->query("SELECT * FROM comments WHERE product_id = " . intval($product['id']) . " ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?php echo htmlspecialchars($product['name']); ?></title></head>
<body>
  <a href="index.php">← Accueil</a>
  <h1><?php echo htmlspecialchars($product['name']); ?></h1>
  <p><?php echo htmlspecialchars($product['description']); ?></p>
  <p>Prix: <?php echo htmlspecialchars($product['price']); ?>€</p>

  <h2>Commentaires</h2>
  <ul>
  <?php while ($c = $comsRes->fetch_assoc()): ?>
    <li>
      <strong><?php echo htmlspecialchars($c['author']); ?></strong> —
      <?php echo $c['content']; ?>
      <small>(<?php echo $c['created_at']; ?>)</small>
    </li>
  <?php endwhile; ?>
  </ul>

  <h3>Ajouter un commentaire</h3>
  <form method="post" action="post_comment.php" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo intval($product['id']); ?>">
    Auteur: <input name="author"><br/>
    Commentaire:<br/>
    <textarea name="content" cols="40" rows="4"></textarea><br/>
    Photo (optionnel, accepte XML pour la demo XXE): <input type="file" name="photo"><br/>
    <button>Envoyer</button>
  </form>
  <hr/>
</body>
</html>
