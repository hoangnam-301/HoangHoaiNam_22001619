BAI THUC HANH PHP + MYSQL - QUAN LY GIO HANG VA DANG NHAP

1. Copy thu muc app vao C:\xampp\htdocs\app.
2. Bat Apache va MySQL trong XAMPP.
3. Mo phpMyAdmin, chon tab Import hoac SQL va chay file app/shopping_cart.sql.
4. Kiem tra thong tin ket noi trong app/common/dbConnect.php. Mac dinh: localhost, user root, password rong.
5. Truy cap http://localhost/app/ de dang nhap.

Tai khoan mau:
- Ten dang nhap: admin
- Mat khau: Admin@123

Mat khau tai khoan mau duoc luu duoi dang hash tao bang password_hash(), khong phai van ban thuần.
Nguoi dung co the dang ky tai khoan moi tai register.php. Cac trang quan ly san pham yeu cau dang nhap.
