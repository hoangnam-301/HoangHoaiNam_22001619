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
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        respond(400, ['success' => false, 'message' => 'Dữ liệu JSON không hợp lệ.']);
    }
    return $data;
}

function validateProduct(array $data): array {
    $name = trim((string)($data['name'] ?? ''));
    $price = $data['price'] ?? null;
    $stock = $data['stock'] ?? null;

    $isInteger = static function ($value): bool {
        return is_int($value) || (is_string($value) && preg_match('/^-?\d+$/', $value));
    };

    if ($name === '' || mb_strlen($name) > 100) {
        respond(422, ['success' => false, 'message' => 'Tên sản phẩm phải có từ 1 đến 100 ký tự.']);
    }
    if (!$isInteger($price) || (int)$price <= 0) {
        respond(422, ['success' => false, 'message' => 'Đơn giá phải là số nguyên lớn hơn 0.']);
    }
    if (!$isInteger($stock) || (int)$stock < 0) {
        respond(422, ['success' => false, 'message' => 'Số lượng tồn phải là số nguyên không âm.']);
    }

    return ['name' => $name, 'price' => (int)$price, 'stock' => (int)$stock];
}

function getId(): int {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id || $id < 1) {
        respond(400, ['success' => false, 'message' => 'Thiếu hoặc mã sản phẩm không hợp lệ.']);
    }
    return $id;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $pageRaw = $_GET['page'] ?? '1';
        $limitRaw = $_GET['limit'] ?? '5';

        if (!is_string($pageRaw) || !preg_match('/^[1-9]\d*$/', $pageRaw)) {
            respond(400, ['success' => false, 'message' => 'page phải là số nguyên dương.']);
        }
        if (!is_string($limitRaw) || !preg_match('/^\d+$/', $limitRaw) || (int)$limitRaw !== 5) {
            respond(400, ['success' => false, 'message' => 'limit phải bằng 5.']);
        }

        $page = (int)$pageRaw;
        $limit = 5;

        $total = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $totalPages = max(1, (int)ceil($total / $limit));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $limit;

        $stmt = $pdo->prepare(
            'SELECT id, name, price, stock, image
             FROM products
             ORDER BY id DESC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        respond(200, [
            'success' => true,
            'data' => $stmt->fetchAll(),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'total_pages' => $totalPages
            ]
        ]);
    }

    if ($method === 'POST') {
        $product = validateProduct(readJson());
        $stmt = $pdo->prepare(
            'INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)'
        );
        $stmt->execute($product);
        respond(201, [
            'success' => true,
            'message' => 'Thêm sản phẩm thành công.',
            'id' => (int)$pdo->lastInsertId()
        ]);
    }

    if ($method === 'PUT') {
        $id = getId();
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
        $id = getId();

        $check = $pdo->prepare('SELECT id, image FROM products WHERE id = :id');
        $check->execute(['id' => $id]);
        $product = $check->fetch();
        if (!$product) {
            respond(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm cần xóa.']);
        }

        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if (!empty($product['image'])) {
            $imagePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $product['image']);
            if (is_file($imagePath)) {
                @unlink($imagePath);
            }
        }

        respond(200, ['success' => true, 'message' => 'Xóa sản phẩm thành công.']);
    }

    header('Allow: GET, POST, PUT, DELETE, OPTIONS');
    respond(405, ['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
} catch (Throwable $e) {
    respond(500, ['success' => false, 'message' => 'Đã xảy ra lỗi máy chủ.']);
}
