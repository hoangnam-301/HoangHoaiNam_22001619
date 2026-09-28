<?php

// CLASS Movie

class Movie
{
    private $id;
    private $title;
    private $price;
    private $totalSeats;
    private $availableSeats;

    public function __construct($id, $title, $price, $totalSeats)
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;

        $this->availableSeats = $totalSeats;
    }

    // Đặt vé
    public function bookTicket($quantity)
    {
        // Kiểm tra số lượng
        if ($quantity <= 0) {
            echo "Không thể đặt vé: số lượng phải lớn hơn 0.<br>";
            return false;
        }

        // Kiểm tra số ghế còn lại
        if ($quantity > $this->availableSeats) {
            echo "Không thể đặt vé: không đủ ghế còn lại.<br>";
            return false;
        }

        // Đặt vé thành công
        $this->availableSeats -= $quantity;

        echo "Đặt thành công $quantity vé cho phim: "
            . $this->title . "<br>";

        return true;
    }

    // Hủy vé
    public function cancelTicket($quantity)
    {
        // Kiểm tra số lượng
        if ($quantity <= 0) {
            echo "Không thể hủy vé: số lượng phải lớn hơn 0.<br>";
            return false;
        }

        // Số vé đã bán
        $soldSeats = $this->getSoldSeats();

        // Không được hủy nhiều hơn số vé đã bán
        if ($quantity > $soldSeats) {
            echo "Không thể hủy vé: số vé hủy lớn hơn số vé đã bán.<br>";
            return false;
        }

        // Hủy vé thành công
        $this->availableSeats += $quantity;

        echo "Đã hủy $quantity vé của phim: "
            . $this->title . "<br>";

        return true;
    }

    // Lấy số vé đã bán
    public function getSoldSeats()
    {
        return $this->totalSeats - $this->availableSeats;
    }

    // Tính doanh thu
    public function getRevenue()
    {
        return $this->getSoldSeats() * $this->price;
    }

    // Hiển thị thông tin phim
    public function displayInfo()
    {
        echo "<h3>" . $this->title . "</h3>";

        echo "Mã phim: " . $this->id . "<br>";

        echo "Tên phim: " . $this->title . "<br>";

        echo "Giá vé: "
            . number_format($this->price, 0, ',', '.')
            . " VNĐ<br>";

        echo "Tổng số ghế: "
            . $this->totalSeats . "<br>";

        echo "Số ghế còn lại: "
            . $this->availableSeats . "<br>";

        echo "Số vé đã bán: "
            . $this->getSoldSeats() . "<br>";

        echo "Doanh thu: "
            . number_format($this->getRevenue(), 0, ',', '.')
            . " VNĐ<br>";
    }

    // Getter ID
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }
}


// TÌM PHIM THEO ID

function findMovieById($movies, $id)
{
    if (empty($movies)) {
        return null;
    }

    foreach ($movies as $movie) {

        if ($movie->getId() == $id) {
            return $movie;
        }
    }

    return null;
}


// TÍNH TỔNG DOANH THU

function getTotalRevenue($movies)
{
    if (empty($movies)) {
        return 0;
    }

    $totalRevenue = 0;

    foreach ($movies as $movie) {
        $totalRevenue += $movie->getRevenue();
    }

    return $totalRevenue;
}


// TÌM PHIM BÁN ĐƯỢC NHIỀU VÉ NHẤT

function getBestSellingMovie($movies)
{
    if (empty($movies)) {
        return null;
    }

    $bestMovie = null;
    $maxSoldSeats = -1;

    foreach ($movies as $movie) {

        if ($movie->getSoldSeats() > $maxSoldSeats) {

            $maxSoldSeats = $movie->getSoldSeats();

            $bestMovie = $movie;
        }
    }

    return $bestMovie;
}


// Tạo danh sách phim

$movies = [

    new Movie(
        1,
        "Avengers",
        100000,
        100
    ),

    new Movie(
        2,
        "Avatar",
        120000,
        80
    ),

    new Movie(
        3,
        "Batman",
        90000,
        120
    )
];


// Đặt vé cho Avengers

echo "<h2>ĐẶT VÉ</h2>";

$avengers = findMovieById($movies, 1);

if ($avengers != null) {

    $avengers->bookTicket(30);
}


// Đặt vé cho Avatar

$avatar = findMovieById($movies, 2);

if ($avatar != null) {

    $avatar->bookTicket(25);
}


// Hủy một số vé Avengers

echo "<h2>HỦY VÉ</h2>";

if ($avengers != null) {

    $avengers->cancelTicket(5);
}


// Hiển thị thông tin tất cả phim

echo "<h2>THÔNG TIN CÁC BỘ PHIM</h2>";

foreach ($movies as $movie) {

    $movie->displayInfo();

    echo "<hr>";
}


// Tính tổng doanh thu

echo "<h2>TỔNG DOANH THU</h2>";

$totalRevenue = getTotalRevenue($movies);

echo number_format(
    $totalRevenue,
    0,
    ',',
    '.'
) . " VNĐ<br>";


// Tìm phim bán nhiều vé nhất

echo "<h2>PHIM BÁN NHIỀU VÉ NHẤT</h2>";

$bestMovie = getBestSellingMovie($movies);

if ($bestMovie != null) {

    $bestMovie->displayInfo();

} else {

    echo "Danh sách phim đang rỗng.";
}


// Kiểm tra các trường hợp không hợp lệ

echo "<h2>KIỂM TRA DỮ LIỆU KHÔNG HỢP LỆ</h2>";


// Đặt số vé <= 0
echo "<strong>1. Đặt 0 vé:</strong><br>";

$avengers->bookTicket(0);

echo "<br>";


// Đặt vượt quá số ghế
echo "<strong>2. Đặt vượt quá số ghế:</strong><br>";

$avengers->bookTicket(1000);

echo "<br>";


// Hủy số vé <= 0
echo "<strong>3. Hủy 0 vé:</strong><br>";

$avengers->cancelTicket(0);

echo "<br>";


// Hủy nhiều hơn số vé đã bán
echo "<strong>4. Hủy nhiều hơn số vé đã bán:</strong><br>";

$avengers->cancelTicket(1000);

echo "<br>";


// Tìm phim không tồn tại
echo "<strong>5. Tìm phim không tồn tại:</strong><br>";

$notFoundMovie = findMovieById($movies, 999);

if ($notFoundMovie == null) {

    echo "Không tìm thấy phim có ID = 999.<br>";
}


// Danh sách phim rỗng
echo "<br>";

echo "<strong>6. Kiểm tra danh sách phim rỗng:</strong><br>";

$emptyMovies = [];

$result = findMovieById($emptyMovies, 1);

if ($result == null) {
    echo "Danh sách phim rỗng hoặc không tìm thấy phim.<br>";
}

echo "Tổng doanh thu: "
    . number_format(
        getTotalRevenue($emptyMovies),
        0,
        ',',
        '.'
    )
    . " VNĐ<br>";

$result = getBestSellingMovie($emptyMovies);

if ($result == null) {
    echo "Không có phim để tìm phim bán chạy nhất.<br>";
}

?>
