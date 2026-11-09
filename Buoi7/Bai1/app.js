const { createApp } = Vue;

createApp({
    data() {
        return {
            trangThai: "Đang tải...",
            tuKhoa: "",
            danhMucChon: "",
            thuTuSapXep: "none",
            thongBao: "",
            loi: "",
            sanPhams: [
                { id: 1, ten: "Laptop Dell", gia: 18000000, danhMuc: "Điện tử", tonKho: 5 },
                { id: 2, ten: "Điện thoại Samsung", gia: 12000000, danhMuc: "Điện tử", tonKho: 8 },
                { id: 3, ten: "Bàn học", gia: 1500000, danhMuc: "Nội thất", tonKho: 0 },
                { id: 4, ten: "Ghế văn phòng", gia: 2200000, danhMuc: "Nội thất", tonKho: 4 },
                { id: 5, ten: "Sách lập trình Java", gia: 180000, danhMuc: "Sách", tonKho: 12 },
                { id: 6, ten: "Sách học Vue.js", gia: 250000, danhMuc: "Sách", tonKho: 0 }
            ],
            sanPhamMoi: { ten: "", gia: null, danhMuc: "", tonKho: null }
        };
    },
    computed: {
        danhMucList() {
            return ["Điện tử", "Nội thất", "Sách"];
        },
        sanPhamHienThi() {
            let ketQua = this.sanPhams.filter(sp => {
                const khopTen = sp.ten.toLowerCase().includes(this.tuKhoa.trim().toLowerCase());
                const khopDanhMuc = this.danhMucChon === "" || sp.danhMuc === this.danhMucChon;
                return khopTen && khopDanhMuc;
            });

            if (this.thuTuSapXep === "asc") {
                ketQua.sort((a, b) => a.gia - b.gia);
            } else if (this.thuTuSapXep === "desc") {
                ketQua.sort((a, b) => b.gia - a.gia);
            }

            return ketQua;
        },
        tongGiaTriTonKho() {
            return this.sanPhamHienThi.reduce((tong, sp) => tong + sp.gia * sp.tonKho, 0);
        }
    },
    watch: {
        tuKhoa(giaTriMoi) {
            if (giaTriMoi.length === 1) {
                this.thongBao = "Bạn mới nhập 1 ký tự. Hãy nhập thêm để tìm kiếm chính xác hơn.";
            } else {
                this.thongBao = "";
            }
        }
    },
    methods: {
        dinhDangTien(giaTri) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND"
            }).format(giaTri);
        },
        themSanPham() {
            this.loi = "";

            if (!this.sanPhamMoi.ten) {
                this.loi = "Tên sản phẩm không được để trống.";
                return;
            }
            if (this.sanPhamMoi.gia === null || this.sanPhamMoi.gia <= 0 || !Number.isFinite(this.sanPhamMoi.gia)) {
                this.loi = "Giá sản phẩm phải là số lớn hơn 0.";
                return;
            }
            if (!Number.isInteger(this.sanPhamMoi.tonKho) || this.sanPhamMoi.tonKho < 0) {
                this.loi = "Số lượng tồn phải là số nguyên không âm.";
                return;
            }
            if (!this.sanPhamMoi.danhMuc) {
                this.loi = "Vui lòng chọn danh mục.";
                return;
            }

            const idMoi = this.sanPhams.length
                ? Math.max(...this.sanPhams.map(sp => sp.id)) + 1
                : 1;

            this.sanPhams.push({
                id: idMoi,
                ten: this.sanPhamMoi.ten,
                gia: this.sanPhamMoi.gia,
                danhMuc: this.sanPhamMoi.danhMuc,
                tonKho: this.sanPhamMoi.tonKho
            });

            this.sanPhamMoi = { ten: "", gia: null, danhMuc: "", tonKho: null };
        },
        xoaSanPham(id) {
            const xacNhan = confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?");
            if (!xacNhan) return;

            this.sanPhams = this.sanPhams.filter(sp => sp.id !== id);
        }
    },
    mounted() {
        this.trangThai = "Sẵn sàng";
    }
}).mount("#app");
