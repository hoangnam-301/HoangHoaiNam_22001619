const DongGioHang = {
    name: "DongGioHang",
    props: {
        sanPham: {
            type: Object,
            required: true
        }
    },
    emits: ["doi-so-luong", "yeu-cau-xoa"],
    methods: {
        dinhDangTien(giaTri) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND"
            }).format(giaTri);
        },
        thayDoiSoLuong(giaTri) {
            const soLuongMoi = Number(giaTri);
            if (!Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) {
                return;
            }
            this.$emit("doi-so-luong", {
                id: this.sanPham.id,
                soLuongMoi: soLuongMoi
            });
        },
        tangSoLuong() {
            this.thayDoiSoLuong(this.sanPham.soLuong + 1);
        },
        giamSoLuong() {
            this.thayDoiSoLuong(this.sanPham.soLuong - 1);
        },
        xoa() {
            this.$emit("yeu-cau-xoa", this.sanPham.id);
        }
    },
    template: `
        <div class="cart-row">
            <div class="product-info">
                <strong>{{ sanPham.ten }}</strong>
                <span>Đơn giá: {{ dinhDangTien(sanPham.donGia) }}</span>
                <span>Thành tiền: <strong>{{ dinhDangTien(sanPham.donGia * sanPham.soLuong) }}</strong></span>
            </div>
            <div class="quantity-control">
                <button @click="giamSoLuong" :disabled="sanPham.soLuong <= 1">−</button>
                <span>{{ sanPham.soLuong }}</span>
                <button @click="tangSoLuong" :disabled="sanPham.soLuong >= 99">+</button>
            </div>
            <button class="delete" @click="xoa">Xóa</button>
        </div>
    `
};
