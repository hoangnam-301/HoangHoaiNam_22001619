<?php
require_once __DIR__ . '/model/product.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = null;
$error = "";

if ($id === false || $id === null) {
    $error = "ID sản phẩm không hợp lệ.";
} else {
    $product = getProductById($id);

    if (!$product) {
        $error = "Sản phẩm không tồn tại.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $confirmId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($confirmId !== $id) {
        $error = "ID sản phẩm không hợp lệ.";
    } elseif (deleteProduct($id)) {
        header("Location: product_list.php?message=" . urlencode("Xóa sản phẩm thành công."));
        exit;
    } else {
        $error = "Không thể xóa sản phẩm.";
    }
}

$pageTitle = "Xóa sản phẩm";
require_once __DIR__ . '/view/header.php';
?>

<h2>Xóa sản phẩm</h2>

<?php if ($error !== ''): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
    <a class="button" href="product_list.php">Quay lại</a>
<?php else: ?>

<p>Bạn có chắc chắn muốn xóa sản phẩm sau không?</p>

<table>
    <tr>
        <th>ID</th>
        <td><?= (int)$product['id'] ?></td>
    </tr>
    <tr>
        <th>Tên sản phẩm</th>
        <td><?= htmlspecialchars($product['name']) ?></td>
    </tr>
    <tr>
        <th>Giá</th>
        <td><?= number_format((float)$product['price'], 2, ',', '.') ?></td>
    </tr>
    <tr>
        <th>Số lượng</th>
        <td><?= (int)$product['quantity'] ?></td>
    </tr>
</table>

<form method="post" style="margin-top: 20px;">
    <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
    <button type="submit">Xác nhận xóa</button>
    <a class="button" href="product_list.php">Hủy</a>
</form>

<?php endif; ?>

<?php require_once __DIR__ . '/view/footer.php'; ?>