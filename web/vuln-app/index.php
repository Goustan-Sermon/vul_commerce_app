<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Chop Shop - Bières</title>
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
    $sql = "SELECT * FROM products WHERE name LIKE '%$q%' OR description LIKE '%$q%' LIMIT 6";
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
</body>
</html>
