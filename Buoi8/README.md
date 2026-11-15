# Bài thực hành 8 – PHP API và Vue.js

## 1. Cấu trúc thư mục
- `database.sql`: tạo CSDL, bảng `products` và 3 dữ liệu mẫu.
- `api/db.php`: cấu hình kết nối MySQL bằng PDO.
- `api/products.php`: API GET, POST, PUT, DELETE, kiểm tra dữ liệu và trả JSON.
- `index.html`: giao diện Vue 3 quản lý sản phẩm.

## 2. Cách chạy bằng XAMPP
1. Giải nén thư mục `thuc_hanh_8_api_vuejs` vào `C:/xampp/htdocs/`.
2. Mở XAMPP, bật Apache và MySQL.
3. Vào `http://localhost/phpmyadmin`, chọn tab Import và nhập file `database.sql`.
4. Mở `api/db.php`, chỉnh `$username` và `$password` đúng cấu hình MySQL của máy.
5. Truy cập `http://localhost/thuc_hanh_8_api_vuejs/`.

Không mở `index.html` trực tiếp bằng `file://`; cần chạy qua Apache để PHP API hoạt động.

## 3. API
- `GET /api/products.php`: lấy danh sách, trả `{success:true, data:[...]}`.
- `POST /api/products.php`: thêm, body JSON gồm `name`, `price`, `stock`.
- `PUT /api/products.php?id=1`: sửa sản phẩm, body JSON cùng cấu trúc.
- `DELETE /api/products.php?id=1`: xóa sản phẩm.

Các lỗi trả JSON có `success:false` và `message`. PHP kiểm tra dữ liệu trước khi ghi DB; truy vấn có tham số dùng PDO prepared statement.

## 4. Kiểm tra theo đề
- Thêm sản phẩm hợp lệ, tải lại trang: dữ liệu vẫn còn trong MySQL.
- Sửa đơn giá: bảng cập nhật giá mới.
- Gửi POST với `stock: -1`: API trả HTTP 422, không thêm bản ghi.
- Xóa sản phẩm, tải lại trang: sản phẩm không còn.

Ví dụ POST hợp lệ:
```json
{"name":"Bút chì","price":3000,"stock":10}
```

Ví dụ POST không hợp lệ:
```json
{"name":"Bút chì lỗi","price":3000,"stock":-1}
```
