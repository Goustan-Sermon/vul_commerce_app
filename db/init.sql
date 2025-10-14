CREATE DATABASE IF NOT EXISTS vulnshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE vulnshop;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2)
);

CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  author VARCHAR(100),
  content TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO products (name, description, price) VALUES
('T-shirt vuln', 'T-shirt demo', 19.90),
('Casquette XSS', 'Casquette demo', 12.50);
