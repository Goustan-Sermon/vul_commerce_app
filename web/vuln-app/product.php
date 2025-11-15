<?php
// product.php
require 'db.php'; // connexion $mysqli

// Récupère l'id du produit (par défaut 1)
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Récupère infos produit
$productRes = $mysqli->query("SELECT * FROM products WHERE id = $id");
if (!$productRes || $productRes->num_rows === 0) {
    http_response_code(404);
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Produit introuvable</title><link rel='stylesheet' href='style.css'></head><body>";
    echo "<header>Chop Shop</header><div class='container'><p>Produit introuvable. <a href='index.php'>Retour</a></p></div></body></html>";
    exit;
}
$product = $productRes->fetch_assoc();

// Récupère commentaires
$comsRes = $mysqli->query("SELECT * FROM comments WHERE product_id = " . intval($product['id']) . " ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Chop Shop — <?php echo htmlspecialchars($product['name']); ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  Chop Shop
  <nav>
    <a href="index.php">Accueil</a>
    <a href="#">Contact</a>
    <a href="#">Panier</a>
  </nav>
</header>

<div class="container" style="grid-template-columns: 1fr; gap: 20px; width: 600px">
  <!-- Product card -->
  <div class="product" style="display:flex; flex-direction:row; gap:20px; padding:20px;">
    <div style="flex:0 0 260px;">
      <!-- Image placeholder -->
      <div style="width:100%; height:220px; border-radius:10px; overflow:hidden; background:linear-gradient(135deg,#f6e3c3,#ffeccf); display:flex; align-items:center; justify-content:center;">
        <div style="text-align:center; color:#8b5e3c;">
          <div style="font-weight:700; font-size:1.1rem;">Image</div>
          <div style="font-size:0.9rem; margin-top:6px;"><?php echo htmlspecialchars($product['name']); ?></div>
        </div>
      </div>
    </div>

    <div class="product-content" style="flex:1; padding:0;">
      <h2><?php echo htmlspecialchars($product['name']); ?></h2>
      <p style="color:#666; margin-bottom:12px;"><?php echo htmlspecialchars($product['description']); ?></p>

      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div class="price" style="font-size:1.4rem;"><?php echo htmlspecialchars(number_format($product['price'],2,',','')); ?>€</div>
        <a href="index.php" style="text-decoration:none; color:#8b5e3c; font-weight:700;">← Retour</a>
      </div>
    </div>
  </div>

  <!-- Comments + form -->
  <div style="grid-column: 1 / -1;">
    <div style="background:#fff; padding:18px; border-radius:12px; box-shadow:0 5px 12px rgba(0,0,0,0.06);">
      <h3 style="margin-bottom:12px; color:#6b4226;">Commentaires</h3>

      <ul style="list-style:none; padding:0; margin:0 0 18px 0;">
        <?php while ($c = $comsRes->fetch_assoc()): ?>
          <li style="padding:12px; border-radius:8px; margin-bottom:10px; background:#fffbe6; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <strong style="color:#6b4226;"><?php echo htmlspecialchars($c['author']); ?></strong>
              <small style="color:#999;"><?php echo $c['created_at']; ?></small>
            </div>
            <div style="margin-top:8px;">
              <?php echo $c['content']; ?>
              <?php if (!empty($c['image'])): ?>
                <br>
                <img src="uploads/<?php echo $c['image']; ?>" style="max-width:150px; margin-top:8px; border-radius:6px;">
              <?php endif; ?>
            </div>
          </li>
        <?php endwhile; ?>

        <?php if ($comsRes->num_rows === 0): ?>
          <li style="padding:12px; border-radius:8px; background:#fffbe6;">Aucun commentaire pour le moment.</li>
        <?php endif; ?>
      </ul>

      <h4 style="margin-top:8px; color:#6b4226;">Ajouter un commentaire</h4>
      <form method="post" action="post_comment.php" enctype="multipart/form-data" style="margin-top:10px; display:flex; flex-direction:column; gap:10px;">
        <input type="hidden" name="product_id" value="<?php echo intval($product['id']); ?>">
        <input name="author" placeholder="Nom..." style="padding:10px; border-radius:8px; border:1px solid #ddd; width:40%; max-width:300px;">
        <textarea name="content" placeholder="Ajouter un commentaire..." rows="4" style="padding:10px; border-radius:8px; border:1px solid #ddd; resize:vertical; max-width:100%;"></textarea>

        <label style="font-size:0.9rem; color:#666;">Photo (optionnel) :</label>
        <input type="file" name="photo" accept="image/*" style="max-width:320px;">
        <label style="font-size:0.6rem; color:#666; font-style:italic;">Format autorisé : jpeg, png, svg</label>
        
        <div style="display:flex; gap:10px; align-items:center;">
          <button type="submit" style="background:#8b5e3c; color:#fff; border:none; padding:10px 18px; border-radius:30px; font-weight:700; cursor:pointer;">Envoyer</button>
        </div>

      </form>
    </div>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> Chop Shop — Bières artisanales & sélection
</footer>
</body>
</html>


