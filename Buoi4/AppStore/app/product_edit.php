<?php
require_once __DIR__ . '/model/product.php';

$pageTitle = "Sửa sản phẩm";
$errors = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if ($id === false || $id === null) {
        $errors[] = "ID sản phẩm không hợp lệ.";
    }

    if ($name === '') {
        $errors[] = "Tên sản phẩm không được rỗng.";
    }

    if ($price === '' || !is_numeric($price) || (float)$price <= 0) {
        $errors[] = "Giá sản phẩm phải lớn hơn 0.";
    }

    if ($quantity === '' || filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) {
        $errors[] = "Số lượng phải là số nguyên lớn hơn hoặc bằng 0.";
    }

    if (empty($errors)) {
        $product = getProductById($id);

        if (!$product) {
            $errors[] = "Sản phẩm không tồn tại.";
        } elseif (updateProduct($id, $name, (float)$price, (int)$quantity)) {
            header("Location: product_list.php?message=" . urlencode("Cập nhật sản phẩm thành công."));
            exit;
        } else {
            $errors[] = "Không thể cập nhật sản phẩm.";
        }
    }
} else {
    if ($id === false || $id === null) {
        $errors[] = "ID sản phẩm không hợp lệ.";
        $product = null;
    } else {
        $product = getProductById($id);

        if (!$product) {
            $errors[] = "Sản phẩm không tồn tại.";
        }
    }

    $name = $product['name'] ?? '';
    $price = $product['price'] ?? '';
    $quantity = $product['quantity'] ?? '';
}

require_once __DIR__ . '/view/header.php';
?>

<h2>Sửa sản phẩm</h2>

<?php if (!empty($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
    <a class="button" href="product_list.php">Quay lại</a>
<?php else: ?>

<form method="post">
    <input type="hidden" name="id" value="<?= (int)$id ?>">

    <div class="form-group">
        <label for="name">Tên sản phẩm</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($name) ?>">
    </div>

    <div class="form-group">
        <label for="price">Giá</label>
        <input type="number" id="price" name="price"
               step="0.01" min="0.01"
               value="<?= htmlspecialchars($price) ?>">
    </div>

    <div class="form-group">
        <label for="quantity">Số lượng</label>
        <input type="number" id="quantity" name="quantity"
               min="0"
               value="<?= htmlspecialchars($quantity) ?>">
    </div>

    <button type="submit">Lưu thay đổi</button>
    <a class="button" href="product_list.php">Quay lại</a>
</form>

<?php endif; ?>

<?php require_once __DIR__ . '/view/footer.php'; ?>