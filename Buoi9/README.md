# Bài thực hành 9 – Upload ảnh và phân trang sản phẩm

## 1. Cấu trúc thư mục

- `index.html`: giao diện Vue 3 quản lý sản phẩm, chọn ảnh, xem trước và phân trang.
- `database.sql`: tạo cơ sở dữ liệu, bảng products có cột image và 12 sản phẩm mẫu.
- `migration.sql`: thêm cột image khi tiếp tục sử dụng cơ sở dữ liệu của Buoi8.
- `api/db.php`: cấu hình kết nối MySQL bằng PDO.
- `api/products.php`: API CRUD và phân trang Backend.
- `api/upload.php`: API upload ảnh sản phẩm.
- `uploads/`: nơi lưu ảnh được tải lên.

## 2. Cách chạy bằng XAMPP

1. Giải nén thư mục `Buoi9` vào `C:/xampp/htdocs/`.
2. Mở XAMPP, bật Apache và MySQL.
3. Nếu muốn tạo cơ sở dữ liệu mới, vào phpMyAdmin, chọn Import và nhập `database.sql`.
4. Nếu muốn tiếp tục dùng cơ sở dữ liệu `thuc_hanh_vue` của Buoi8, không import lại `database.sql`. Hãy chọn database trong phpMyAdmin và chạy `migration.sql` để thêm cột image. Sau đó bổ sung dữ liệu để có ít nhất 12 sản phẩm.
5. Mở `api/db.php`, chỉnh `$username` và `$password` theo cấu hình MySQL trên máy.
6. Đảm bảo thư mục `uploads` có quyền ghi.
7. Truy cập `http://localhost/Buoi9/`.

Không mở `index.html` trực tiếp bằng `file://`; cần chạy qua Apache để PHP API hoạt động.

## 3. API

### GET api/products.php?page=1&limit=5

Lấy danh sách sản phẩm theo trang. `page` là số nguyên dương, `limit` bắt buộc bằng 5. Khi thiếu tham số, mặc định page = 1 và limit = 5.

Phản hồi gồm `success`, `data` và `pagination` (page, limit, total, total_pages). Danh sách được sắp xếp theo id giảm dần. Nếu page vượt trang cuối, API tự đưa về trang cuối hợp lệ.

### POST api/products.php

Thêm sản phẩm bằng JSON gồm `name`, `price`, `stock`. Phản hồi có `id` của sản phẩm vừa thêm.

### PUT api/products.php?id=1

Cập nhật thông tin sản phẩm bằng JSON. Không cập nhật cột image nên ảnh hiện tại được giữ nguyên nếu không tải ảnh mới.

### DELETE api/products.php?id=1

Xóa sản phẩm và ảnh tương ứng nếu có.

### POST api/upload.php

Gửi `multipart/form-data` gồm:
- `id`: mã sản phẩm.
- `image`: tệp ảnh JPG/JPEG/PNG, tối đa 2 MB.

PHP kiểm tra sản phẩm tồn tại, dung lượng, MIME thực tế và nội dung ảnh. Tên ảnh được tạo duy nhất. Chỉ cập nhật đường dẫn ảnh trong database sau khi lưu tệp thành công.

## 4. Kiểm tra bài thực hành

- Thêm sản phẩm không có ảnh.
- Thêm sản phẩm có ảnh PNG/JPG hợp lệ.
- Thử tải tệp sai định dạng hoặc ảnh lớn hơn 2 MB.
- Sửa thông tin nhưng không chọn ảnh mới, ảnh cũ phải còn.
- Thử upload ảnh mới cho sản phẩm đã có ảnh.
- Mở danh sách 12 sản phẩm: trang 1 có 5, trang 2 có 5, trang 3 có 2.
- Xóa sản phẩm duy nhất ở trang cuối và kiểm tra hệ thống tự chuyển về trang hợp lệ.
- Thử API với `page=0`, `page=abc`, `limit=10` để kiểm tra thông báo lỗi.

## 5. Lưu ý cấu hình PHP

Trong `php.ini`, `upload_max_filesize` và `post_max_size` cần lớn hơn 2 MB. Có thể đặt `upload_max_filesize = 2M` và `post_max_size = 3M`, sau đó khởi động lại Apache.
