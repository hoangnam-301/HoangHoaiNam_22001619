<?php
require_once __DIR__ . '/model/product.php';

$pageTitle = "Thêm sản phẩm";
$errors = [];

$name = "";
$price = "";
$quantity = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

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
        if (addProduct($name, (float)$price, (int)$quantity)) {
            header("Location: product_list.php?message=" . urlencode("Thêm sản phẩm thành công."));
            exit;
        }

        $errors[] = "Không thể thêm sản phẩm.";
    }
}

require_once __DIR__ . '/view/header.php';
?>

<h2>Thêm sản phẩm</h2>

<?php if (!empty($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">
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

    <button type="submit">Thêm sản phẩm</button>
    <a class="button" href="product_list.php">Quay lại</a>
</form>

<?php require_once __DIR__ . '/view/footer.php'; ?>