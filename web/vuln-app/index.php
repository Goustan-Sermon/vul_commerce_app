<?php
require 'db.php';

$term = isset($_GET['q']) ? $_GET['q'] : '';

if ($term !== '') {
    $sql = "SELECT * FROM products WHERE name LIKE '%$term%' OR description LIKE '%$term%'";
    $res = $mysqli->query($sql);
} else {
    $res = $mysqli->query("SELECT * FROM products LIMIT 10");
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Vuln Shop</title></head>
<body>
  <h1>Chop shop</h1>

  <form method="get">
    Recherche: <input name="q" value="<?php echo htmlspecialchars($term); ?>">
    <button>Go</button>
  </form>

  <h2>Produits</h2>
  <ul>
  <?php while ($p = $res->fetch_assoc()): ?>
    <li>
      <a href="product.php?id=<?php echo $p['id']; ?>">
        <?php echo htmlspecialchars($p['name']); ?>
      </a>
      — <?php echo htmlspecialchars($p['price']); ?>€
    </li>
  <?php endwhile; ?>
  </ul>

  <hr/>
</body>
</html>
