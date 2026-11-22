CREATE DATABASE IF NOT EXISTS thuc_hanh_vue
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE thuc_hanh_vue;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255) NULL,
    CONSTRAINT chk_products_price CHECK (price > 0),
    CONSTRAINT chk_products_stock CHECK (stock >= 0)
) ENGINE=InnoDB;

INSERT INTO products (name, price, stock) VALUES
('Bút bi Thiên Long', 5000, 20),
('Vở ô ly 200 trang', 12000, 15),
('Thước kẻ 20 cm', 8000, 0),
('Tẩy trắng', 4000, 30),
('Bút chì 2B', 6000, 25),
('Hộp bút vải', 35000, 10),
('Compa học sinh', 18000, 12),
('Màu sáp 12 màu', 25000, 8),
('Giấy kiểm tra', 10000, 40),
('Kéo thủ công', 15000, 14),
('Bút dạ quang', 9000, 18),
('Ba lô học sinh', 185000, 5);
