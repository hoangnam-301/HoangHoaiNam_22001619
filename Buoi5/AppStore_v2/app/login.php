<?php
require_once __DIR__ . '/common/auth.php';
require_once __DIR__ . '/common/dbConnect.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: product_list.php');
    exit;
}

$pageTitle = 'Đăng nhập';
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: product_list.php');
            exit;
        }

        $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
    }
}

require_once __DIR__ . '/view/header.php';
?>
<h2>Đăng nhập</h2>

<?php if (isset($_GET['registered'])): ?>
    <div class="message">Đăng ký thành công. Vui lòng đăng nhập.</div>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<form method="post" action="login.php">
    <div class="form-group">
        <label for="username">Tên đăng nhập</label>
        <input type="text" id="username" name="username" maxlength="50" required
               value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Đăng nhập</button>
    <a class="button" href="register.php">Đăng ký tài khoản</a>
</form>
<?php require_once __DIR__ . '/view/footer.php'; ?>
