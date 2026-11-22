<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

function respond(int $status, array $payload): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST, OPTIONS');
    respond(405, ['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
}

require_once __DIR__ . '/db.php';

$idRaw = $_POST['id'] ?? '';
if (!is_string($idRaw) || !preg_match('/^[1-9]\d*$/', $idRaw)) {
    respond(400, ['success' => false, 'message' => 'Mã sản phẩm không hợp lệ.']);
}
$id = (int)$idRaw;

$check = $pdo->prepare('SELECT id, image FROM products WHERE id = :id');
$check->execute(['id' => $id]);
$product = $check->fetch();
if (!$product) {
    respond(404, ['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
}

if (!isset($_FILES['image'])) {
    respond(400, ['success' => false, 'message' => 'Vui lòng chọn ảnh cần tải lên.']);
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    $messages = [
        UPLOAD_ERR_INI_SIZE => 'Ảnh vượt quá dung lượng cho phép của máy chủ.',
        UPLOAD_ERR_FORM_SIZE => 'Ảnh vượt quá dung lượng cho phép.',
        UPLOAD_ERR_PARTIAL => 'Ảnh tải lên chưa hoàn tất.',
        UPLOAD_ERR_NO_FILE => 'Vui lòng chọn ảnh cần tải lên.'
    ];
    respond(400, ['success' => false, 'message' => $messages[$file['error']] ?? 'Không thể tải ảnh lên.']);
}

$maxSize = 2 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    respond(422, ['success' => false, 'message' => 'Dung lượng ảnh tối đa là 2 MB.']);
}

$tmpPath = $file['tmp_name'];
if (!is_uploaded_file($tmpPath)) {
    respond(400, ['success' => false, 'message' => 'Tệp tải lên không hợp lệ.']);
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($tmpPath);
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png'
];

if (!isset($allowed[$mime])) {
    respond(422, ['success' => false, 'message' => 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG.']);
}

$imageInfo = @getimagesize($tmpPath);
if ($imageInfo === false || !isset($imageInfo['mime']) || $imageInfo['mime'] !== $mime) {
    respond(422, ['success' => false, 'message' => 'Tệp tải lên không phải ảnh hợp lệ.']);
}

$uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    respond(500, ['success' => false, 'message' => 'Không thể tạo thư mục uploads.']);
}

$filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
$destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;
$relativePath = 'uploads/' . $filename;

if (!move_uploaded_file($tmpPath, $destination)) {
    respond(500, ['success' => false, 'message' => 'Không thể lưu ảnh lên máy chủ.']);
}

try {
    $update = $pdo->prepare('UPDATE products SET image = :image WHERE id = :id');
    $update->execute(['image' => $relativePath, 'id' => $id]);
} catch (Throwable $e) {
    @unlink($destination);
    respond(500, ['success' => false, 'message' => 'Không thể cập nhật đường dẫn ảnh.']);
}

if (!empty($product['image'])) {
    $oldPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $product['image']);
    if (is_file($oldPath) && realpath($oldPath) !== realpath($destination)) {
        @unlink($oldPath);
    }
}

respond(200, [
    'success' => true,
    'message' => 'Tải ảnh lên thành công.',
    'image' => $relativePath
]);
