-- BÀI 1: QUẢN LÝ GIỎ HÀNG

-- 1. Tạo database
CREATE DATABASE IF NOT EXISTS shopping_cart;

USE shopping_cart;

-- Xóa bảng cũ nếu đã tồn tại
DROP TABLE IF EXISTS cart_items;

-- 2. Tạo bảng cart_items
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- 3. Thêm ít nhất 5 sản phẩm
INSERT INTO cart_items (name, price, quantity)
VALUES
    ('Laptop Dell Inspiron', 15000000, 2),
    ('Chuột Logitech', 450000, 8),
    ('Bàn phím cơ', 1200000, 6),
    ('Tai nghe Bluetooth', 850000, 4),
    ('Màn hình Samsung', 5500000, 3),
    ('USB 64GB', 180000, 10);

-- 4. Hiển thị toàn bộ sản phẩm
SELECT *
FROM cart_items;

-- 5. Hiển thị sản phẩm có giá lớn hơn 100000
SELECT *
FROM cart_items
WHERE price > 100000;

-- 6. Hiển thị sản phẩm có số lượng lớn hơn 5
SELECT *
FROM cart_items
WHERE quantity > 5;

-- 7. Sắp xếp sản phẩm theo giá giảm dần
SELECT *
FROM cart_items
ORDER BY price DESC;

-- 8. Cập nhật giá của một sản phẩm
UPDATE cart_items
SET price = 500000
WHERE id = 2;

-- Kiểm tra kết quả
SELECT *
FROM cart_items
WHERE id = 2;

-- 9. Cập nhật số lượng của một sản phẩm
UPDATE cart_items
SET quantity = 12
WHERE id = 4;

-- Kiểm tra kết quả
SELECT *
FROM cart_items
WHERE id = 4;

-- 10. Xóa một sản phẩm
DELETE FROM cart_items
WHERE id = 6;

-- Kiểm tra kết quả
SELECT *
FROM cart_items;

-- 11. Hiển thị tên, giá, số lượng và thành tiền
SELECT
    name,
    price,
    quantity,
    price * quantity AS total
FROM cart_items;

-- 12. Tính tổng tiền của toàn bộ giỏ hàng
SELECT
    SUM(price * quantity) AS total_cart
FROM cart_items;

-- BÀI 2: QUẢN LÝ VÉ XEM PHIM

-- 1. Tạo database
CREATE DATABASE IF NOT EXISTS movie_ticket;

USE movie_ticket;

-- Xóa bảng cũ nếu đã tồn tại
DROP TABLE IF EXISTS movies;

-- 2. Tạo bảng movies
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- 3. Thêm ít nhất 5 bộ phim
INSERT INTO movies (title, price, total_seats, available_seats)
VALUES
    ('Avengers: Endgame', 120000, 150, 30),
    ('Avatar: The Way of Water', 150000, 200, 80),
    ('Spider-Man: No Way Home', 110000, 120, 20),
    ('Interstellar', 100000, 100, 65),
    ('The Batman', 130000, 180, 90),
    ('Dune: Part Two', 140000, 160, 40);

-- 4. Hiển thị toàn bộ danh sách phim
SELECT *
FROM movies;

-- 5. Hiển thị phim có giá vé lớn hơn 100000
SELECT *
FROM movies
WHERE price > 100000;

-- 6. Hiển thị phim còn nhiều hơn 50 ghế
SELECT *
FROM movies
WHERE available_seats > 50;

-- 7. Sắp xếp phim theo giá vé giảm dần
SELECT *
FROM movies
ORDER BY price DESC;

-- 8. Cập nhật số ghế còn lại của một phim
UPDATE movies
SET available_seats = 25
WHERE id = 1;

-- Kiểm tra kết quả
SELECT *
FROM movies
WHERE id = 1;

-- 9. Xóa một phim
DELETE FROM movies
WHERE id = 6;

-- Kiểm tra kết quả
SELECT *
FROM movies;

-- 10. Hiển thị số vé đã bán của từng phim
SELECT
    title,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_tickets
FROM movies;

-- 11. Tính doanh thu của từng phim
SELECT
    title,
    price,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_tickets,
    (total_seats - available_seats) * price AS revenue
FROM movies;

-- 12. Tính tổng doanh thu của tất cả các phim
SELECT
    SUM((total_seats - available_seats) * price) AS total_revenue
FROM movies;

-- 13. Tìm phim có số vé bán ra nhiều nhất
SELECT
    title,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_tickets
FROM movies
WHERE total_seats - available_seats = (
    SELECT MAX(total_seats - available_seats)
    FROM movies
);