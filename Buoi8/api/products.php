<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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

require_once __DIR__ . '/db.php';

function readJson(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        respond(400, ['success' => false, 'message' => 'Dữ liệu JSON không hợp lệ.']);
    }
    return $data;
}

function validateProduct(array $data): array {
    $name = trim((string)($data['name'] ?? ''));
    $price = $data['price'] ?? null;
    $stock = $data['stock'] ?? null;

    if ($name === '' || mb_strlen($name) > 100) {
        respond(422, ['success' => false, 'message' => 'Tên sản phẩm phải có từ 1 đến 100 ký tự.']);
    }

    // Chấp nhận số nguyên JSON hoặc chuỗi chỉ gồm chữ số; không chấp nhận số thực.
    $isInteger = static function ($value): bool {
        return (is_int($value) || (is_string($value) && preg_match('/^-?\d+$/', $value)));
    };

    if (!$isInteger($price) || (int)$price <= 0) {
        respond(422, ['success' => false, 'message' => 'Đơn giá phải là số nguyên lớn hơn 0.']);
    }
    if (!$isInteger($stock) || (int)$stock < 0) {
        respond(422, ['success' => false, 'message' => 'Số lượng tồn phải là số nguyên không âm.']);
    }

    return ['name' => $name, 'price' => (int)$price, 'stock' => (int)$stock];
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id ASC');
        respond(200, ['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'POST') {
        $product = validateProduct(readJson());
        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $stmt->execute($product);
        respond(201, [
            'success' => true,
            'message' => 'Thêm sản phẩm thành công.',
            'id' => (int)$pdo->lastInsertId()
        ]);
    }

    if ($method === 'PUT') {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            respond(400, ['success' => false, 'message' => 'Thiếu hoặc mã sản phẩm không hợp lệ.']);
        }

        $product = validateProduct(readJson());
        $check = $pdo->prepare('SELECT id FROM products WHERE id = :id');
        $check->execute(['id' => $id]);
        if (!$check->fetch()) {
            respond(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm cần sửa.']);
        }

        $stmt = $pdo->prepare(
            'UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id'
        );
        $stmt->execute($product + ['id' => $id]);
        respond(200, ['success' => true, 'message' => 'Cập nhật sản phẩm thành công.']);
    }

    if ($method === 'DELETE') {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            respond(400, ['success' => false, 'message' => 'Thiếu hoặc mã sản phẩm không hợp lệ.']);
        }

        $check = $pdo->prepare('SELECT id FROM products WHERE id = :id');
        $check->execute(['id' => $id]);
        if (!$check->fetch()) {
            respond(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm cần xóa.']);
        }

        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        respond(200, ['success' => true, 'message' => 'Xóa sản phẩm thành công.']);
    }

    header('Allow: GET, POST, PUT, DELETE, OPTIONS');
    respond(405, ['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
} catch (Throwable $e) {
    respond(500, ['success' => false, 'message' => 'Đã xảy ra lỗi máy chủ.']);
}
