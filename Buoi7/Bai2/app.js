const { createApp } = Vue;

createApp({
    components: {
        "the-san-pham": TheSanPham
    },
    data() {
        return {
            danhSachSanPham: [
                {
                    id: 1,
                    ten: "Laptop Dell Inspiron",
                    gia: 18000000,
                    anh: "https://placehold.co/600x400?text=Laptop+Dell",
                    moTa: "Laptop phục vụ học tập và làm việc, thiết kế gọn nhẹ, hiệu năng ổn định.",
                    soLuongTon: 5
                },
                {
                    id: 2,
                    ten: "Điện thoại Samsung",
                    gia: 12000000,
                    anh: "https://placehold.co/600x400?text=Samsung",
                    moTa: "Điện thoại thông minh với màn hình sắc nét và camera chất lượng.",
                    soLuongTon: 0
                },
                {
                    id: 3,
                    ten: "Bàn học gỗ",
                    gia: 1500000,
                    anh: "https://placehold.co/600x400?text=Ban+hoc",
                    moTa: "Bàn học bằng gỗ, phù hợp với không gian học tập tại nhà.",
                    soLuongTon: 3
                },
                {
                    id: 4,
                    ten: "Tai nghe Bluetooth",
                    gia: 850000,
                    anh: "https://placehold.co/600x400?text=Tai+nghe",
                    moTa: "Tai nghe không dây nhỏ gọn, thuận tiện khi nghe nhạc và học trực tuyến.",
                    soLuongTon: 7
                }
            ]
        };
    }
}).mount("#app");
