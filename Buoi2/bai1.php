<?php

// CLASS CartItem

class CartItem
{
    private $name;
    private $price;
    private $quantity;

    public function __construct($name, $price, $quantity)
    {
        if ($price <= 0) {
            throw new InvalidArgumentException("Giá sản phẩm phải lớn hơn 0.");
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException("Số lượng sản phẩm phải lớn hơn 0.");
        }

        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    // Tính thành tiền
    public function getTotal()
    {
        return $this->price * $this->quantity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
}


// CLASS ShoppingCart

class ShoppingCart
{
    private $items = [];

    // Thêm sản phẩm vào giỏ hàng
    public function addItem($item)
    {
        if (!($item instanceof CartItem)) {
            echo "Lỗi: Chỉ được thêm object CartItem vào giỏ hàng.<br>";
            return;
        }

        $this->items[] = $item;

        echo "Đã thêm sản phẩm: " . $item->getName() . "<br>";
    }

    // Xóa sản phẩm theo tên
    public function removeItem($name)
    {
        foreach ($this->items as $index => $item) {
            if ($item->getName() == $name) {
                unset($this->items[$index]);

                // Đánh lại chỉ số mảng
                $this->items = array_values($this->items);

                echo "Đã xóa sản phẩm: " . $name . "<br>";
                return;
            }
        }

        echo "Không tìm thấy sản phẩm: " . $name . "<br>";
    }

    // Tính tổng tiền
    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->items as $item) {
            // Gọi getTotal() của CartItem
            $total += $item->getTotal();
        }

        return $total;
    }

    // Hiển thị giỏ hàng
    public function displayCart()
    {
        echo "<h2>THÔNG TIN GIỎ HÀNG</h2>";

        if (empty($this->items)) {
            echo "Giỏ hàng đang trống.<br>";
            return;
        }

        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr>";
        echo "<th>Tên sản phẩm</th>";
        echo "<th>Đơn giá</th>";
        echo "<th>Số lượng</th>";
        echo "<th>Thành tiền</th>";
        echo "</tr>";

        foreach ($this->items as $item) {
            echo "<tr>";

            echo "<td>" . $item->getName() . "</td>";

            echo "<td>"
                . number_format($item->getPrice(), 0, ',', '.')
                . " VNĐ</td>";

            echo "<td>" . $item->getQuantity() . "</td>";

            echo "<td>"
                . number_format($item->getTotal(), 0, ',', '.')
                . " VNĐ</td>";

            echo "</tr>";
        }

        echo "</table>";

        echo "<p><strong>Tổng tiền: "
            . number_format($this->calculateTotal(), 0, ',', '.')
            . " VNĐ</strong></p>";
    }
}

try {

    // Tạo object ShoppingCart
    $cart = new ShoppingCart();

    // Tạo ít nhất 4 object CartItem
    $item1 = new CartItem("Laptop", 15000000, 1);
    $item2 = new CartItem("Chuột máy tính", 300000, 2);
    $item3 = new CartItem("Bàn phím", 800000, 1);
    $item4 = new CartItem("Tai nghe", 1200000, 1);

    // Thêm sản phẩm vào giỏ hàng
    echo "<h2>THÊM SẢN PHẨM</h2>";

    $cart->addItem($item1);
    $cart->addItem($item2);
    $cart->addItem($item3);
    $cart->addItem($item4);

    echo "<hr>";

    // Hiển thị toàn bộ giỏ hàng
    $cart->displayCart();

    echo "<hr>";

    // Tính và hiển thị tổng tiền
    echo "<h3>Tổng tiền giỏ hàng:</h3>";

    echo number_format(
        $cart->calculateTotal(),
        0,
        ',',
        '.'
    ) . " VNĐ";

    echo "<hr>";

    // Xóa một sản phẩm
    echo "<h2>XÓA SẢN PHẨM</h2>";

    $cart->removeItem("Bàn phím");

    echo "<hr>";

    // Hiển thị lại giỏ hàng
    $cart->displayCart();

    echo "<hr>";

    // Kiểm tra các trường hợp không hợp lệ

    echo "<h2>KIỂM TRA DỮ LIỆU KHÔNG HỢP LỆ</h2>";

    // Thử xóa sản phẩm không tồn tại
    $cart->removeItem("Điện thoại");

    // Thử tạo sản phẩm có giá <= 0
    try {
        $testItem = new CartItem("Sản phẩm lỗi", 0, 1);
    } catch (InvalidArgumentException $e) {
        echo "Lỗi: " . $e->getMessage() . "<br>";
    }

    // Thử tạo sản phẩm có số lượng <= 0
    try {
        $testItem = new CartItem("Sản phẩm lỗi", 100000, 0);
    } catch (InvalidArgumentException $e) {
        echo "Lỗi: " . $e->getMessage() . "<br>";
    }

} catch (Exception $e) {

    echo "Có lỗi xảy ra: " . $e->getMessage();

}
?>
