<?php
require_once __DIR__ . '/common/auth.php';
require_once __DIR__ . '/common/dbConnect.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: product_list.php');
    exit;
}

$pageTitle = 'Đăng ký tài khoản';
$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors[] = 'Tên đăng nhập không được để trống.';
    } elseif (mb_strlen($username) > 50) {
        $errors[] = 'Tên đăng nhập không được vượt quá 50 ký tự.';
    }

    if (trim($password) === '') {
        $errors[] = 'Mật khẩu không được để trống.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            $errors[] = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->bind_param('ss', $username, $passwordHash);

            if ($stmt->execute()) {
                $stmt->close();
                header('Location: login.php?registered=1');
                exit;
            }

            $stmt->close();
            $errors[] = 'Đăng ký chưa thành công. Vui lòng thử lại.';
        }
    }
}

require_once __DIR__ . '/view/header.php';
?>
<h2>Đăng ký tài khoản</h2>

<?php if ($errors): ?>
    <div class="error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" action="register.php">
    <div class="form-group">
        <label for="username">Tên đăng nhập</label>
        <input type="text" id="username" name="username" maxlength="50" required
               value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Đăng ký</button>
    <a class="button" href="login.php">Đến trang đăng nhập</a>
</form>
<?php require_once __DIR__ . '/view/footer.php'; ?>
