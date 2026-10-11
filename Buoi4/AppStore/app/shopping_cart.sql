CREATE DATABASE IF NOT EXISTS shopping_cart
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE shopping_cart;

DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO products (name, price, quantity) VALUES
('Laptop Dell Inspiron', 18500000.00, 5),
('Chuột Logitech G102', 450000.00, 12),
('Bàn phím cơ AKKO', 1590000.00, 8),
('Tai nghe HyperX Cloud II', 1890000.00, 6),
('Màn hình LG 24 inch', 3250000.00, 10);
