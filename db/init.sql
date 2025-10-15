CREATE DATABASE IF NOT EXISTS vulnshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE vulnshop;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  price DECIMAL(10,2)
);

CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  author VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


INSERT INTO products (name, description, price) VALUES
('Goudale', 'biere de malade qui titre a 6.7%', 4.50),
('Kronenbourg', 'Biere de merde degueulasse', 0.10),
('Lager Chop Shop', 'Bière blonde légère avec notes maltées', 3.50),
('IPA Hop Explosion', 'Bière ambrée très houblonnée', 4.20),
('Stout Nocturne', 'Bière noire, goût café et chocolat', 4.80),
('Wheat Summer Ale', 'Bière de blé, rafraîchissante et fruitée', 3.90),
('Pale Ale Golden', 'Bière dorée, équilibre malt et houblon', 4.00),
('Amber Chop Brew', 'Bière ambrée douce, arômes caramélisés', 4.10),
('Blonde Houblonnée', 'Bière blonde avec un léger goût fruité', 3.60),
('Triple Artisanale', 'Bière triple forte et aromatique', 5.20),
('Porter Noire', 'Bière noire, arômes de chocolat et caramel', 4.70);
;
