const { createApp } = Vue;

createApp({
    components: {
        "dong-gio-hang": DongGioHang
    },
    data() {
        return {
            gioHang: [
                { id: 1, ten: "Bút", donGia: 5000, soLuong: 2 },
                { id: 2, ten: "Quyển vở", donGia: 12000, soLuong: 3 }
            ]
        };
    },
    computed: {
        tongSoLuong() {
            return this.gioHang.reduce((tong, sp) => tong + sp.soLuong, 0);
        },
        tongTien() {
            return this.gioHang.reduce((tong, sp) => tong + sp.donGia * sp.soLuong, 0);
        }
    },
    methods: {
        capNhatSoLuong(thongTin) {
            const { id, soLuongMoi } = thongTin;

            if (!Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) {
                return;
            }

            const sanPham = this.gioHang.find(sp => sp.id === id);
            if (sanPham) {
                sanPham.soLuong = soLuongMoi;
            }
        },
        xoaSanPham(id) {
            this.gioHang = this.gioHang.filter(sp => sp.id !== id);
        },
        dinhDangTien(giaTri) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND"
            }).format(giaTri);
        }
    }
}).mount("#app");
