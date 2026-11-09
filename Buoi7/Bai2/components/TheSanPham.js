const TheSanPham = {
    name: "TheSanPham",
    props: {
        ten: {
            type: String,
            required: true
        },
        gia: {
            type: Number,
            required: true
        },
        anh: {
            type: String,
            default: "https://placehold.co/600x400?text=San+pham"
        },
        moTa: {
            type: String,
            default: "Chưa có mô tả cho sản phẩm này."
        },
        soLuongTon: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            hienMoTa: false
        };
    },
    methods: {
        dinhDangTien(giaTri) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND"
            }).format(giaTri);
        }
    },
    template: `
        <article class="product-card">
            <img :src="anh" :alt="ten">
            <div class="product-content">
                <h2>{{ ten }}</h2>
                <p class="price">{{ dinhDangTien(gia) }}</p>
                <p>
                    <span v-if="soLuongTon > 0" class="badge in-stock">Còn hàng ({{ soLuongTon }})</span>
                    <span v-else class="badge out-stock">Hết hàng</span>
                </p>
                <button class="secondary" @click="hienMoTa = !hienMoTa">
                    {{ hienMoTa ? "Ẩn mô tả" : "Xem mô tả" }}
                </button>
                <p v-if="hienMoTa" class="description">{{ moTa }}</p>
                <button class="buy" :disabled="soLuongTon === 0">Mua</button>
            </div>
        </article>
    `
};
