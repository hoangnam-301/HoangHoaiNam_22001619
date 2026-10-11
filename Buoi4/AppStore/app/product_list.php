<?php
require_once __DIR__ . '/model/product.php';

$pageTitle = "Danh sách sản phẩm";
$products = getAllProducts();

require_once __DIR__ . '/view/header.php';
?>

<h2>Danh sách sản phẩm</h2>

<?php if (isset($_GET['message'])): ?>
    <div class="message">
        <?= htmlspecialchars($_GET['message']) ?>
    </div>
<?php endif; ?>

<a class="button" href="product_add.php">+ Thêm sản phẩm</a>

<?php if (empty($products)): ?>
    <p>Chưa có sản phẩm nào.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Chức năng</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= (int)$product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= number_format((float)$product['price'], 2, ',', '.') ?></td>
            <td><?= (int)$product['quantity'] ?></td>
            <td>
                <a class="button button-edit"
                   href="product_edit.php?id=<?= (int)$product['id'] ?>">Sửa</a>
                <a class="button button-delete"
                   href="product_delete.php?id=<?= (int)$product['id'] ?>">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once __DIR__ . '/view/footer.php'; ?>