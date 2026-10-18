<?php
require_once __DIR__ . '/../common/auth.php';
if (!isset($pageTitle)) {
    $pageTitle = 'Quản lý giỏ hàng';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <h1>QUẢN LÝ GIỎ HÀNG</h1>
    <nav>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="product_list.php">Danh sách sản phẩm</a>
            <a href="product_add.php">Thêm sản phẩm</a>
            <span class="account-name">Tài khoản: <?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <a href="logout.php">Đăng xuất</a>
        <?php else: ?>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
