<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Chop Shop - Bières</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="header-left">
        <span class="logo">Chop Shop</span>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="#">Contact</a>
            <a href="#">Panier</a>
        </nav>
    </div>
    <nav class="header-right">
        <a href="login.php">Se connecter</a>
    </nav>
</header>

<div class="container">
    <div class="search-bar" style="grid-column:1/-1;">
        <form method="get" action="index.php">
            <input type="text" name="q" placeholder="Rechercher une bière...">
            <input type="submit" value="Rechercher">
        </form>
    </div>

    <?php
    $conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));
    if ($conn->connect_error) { die("Erreur : " . $conn->connect_error); }
    $conn->set_charset("utf8mb4");

    $q = $_GET['q'] ?? '';
    $sql = "SELECT * FROM products WHERE name LIKE '%$q%' OR description LIKE '%$q%' LIMIT 3";
    $res = $conn->query($sql);

    while ($p = $res->fetch_assoc()):
        $link = "product.php?id=" . intval($p['id']);
    ?>
        <div class="product">
            <a href="<?php echo $link; ?>">
                <div class="product-content">
                    <h2><?php echo htmlspecialchars($p['name']); ?></h2>
                    <p><?php echo htmlspecialchars($p['description']); ?></p>
                    <div class="price"><?php echo htmlspecialchars(number_format($p['price'],2,',','')); ?>€</div>
                </div>
            </a>
        </div>
    <?php endwhile; ?>
</div>

<footer>
    &copy; <?php echo date('Y'); ?> Chop Shop - Tous droits réservés
</footer>
<script>

(function(){
  const CONTAINER_SELECTOR = '.container';
  const CARD_SELECTOR = '.product';
  const INNER_SELECTOR = '.product-content';

  function debounce(fn, wait){ let t; return (...args)=>{ clearTimeout(t); t = setTimeout(()=>fn(...args), wait); }; }

  function getColumnCount(container){
    const cs = window.getComputedStyle(container);
    const cols = cs.gridTemplateColumns;
    if (!cols) return 1;
    return cols.split(' ').length;
  }

  function adjustCards(){
    const container = document.querySelector(CONTAINER_SELECTOR);
    if (!container) return;
    const cards = Array.from(container.querySelectorAll(CARD_SELECTOR));
    const columnCount = getColumnCount(container);
    const containerWidth = container.clientWidth;
    const columnWidth = containerWidth / columnCount;

    cards.forEach(card => {
      card.style.gridColumn = '';
      card.removeAttribute('data-span');

      const inner = card.querySelector(INNER_SELECTOR) || card;
      const needed = inner.scrollWidth + 40;
      const current = card.clientWidth;

      if (needed > current + 2) {
        let span = Math.ceil(needed / columnWidth);
        if (span > columnCount) span = columnCount;
        if (span > 1) {
          card.style.gridColumn = 'span ' + span;
          card.setAttribute('data-span', span);
        }
      }
    });
  }

  const debouncedAdjust = debounce(adjustCards, 150);
  window.addEventListener('load', adjustCards);
  window.addEventListener('resize', debouncedAdjust);

  window.adjustProductCards = adjustCards;
})();
</script>

</body>
</html>
