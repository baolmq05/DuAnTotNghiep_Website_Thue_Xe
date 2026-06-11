<template>
    <div
        class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
        <div class="relative overflow-hidden aspect-[16/10.5]">
            <img :src="image" :alt="name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>

            <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                <span v-if="isInstantBook"
                    class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm uppercase tracking-wider">
                    Đặt nhanh
                </span>
                <span v-if="isDelivery"
                    class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm uppercase tracking-wider">
                    Giao tận nơi
                </span>
            </div>

            <div
                class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-1.5 shadow-lg border border-white/25">
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Giá từ</p>
                <p class="font-extrabold text-base text-[#286874] leading-none">
                    {{ formatPrice(price) }}
                    <span class="text-xs text-slate-500 font-normal">/ngày</span>
                </p>
            </div>
        </div>

        <div class="p-4 flex flex-col flex-grow">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-grow">
                    <h3
                        class="font-bold text-base text-slate-800 line-clamp-1 group-hover:text-[#286874] transition-colors">
                        {{ name }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        📍 {{ location }}
                    </p>
                </div>

                <div
                    class="flex items-center gap-1 bg-amber-50 text-amber-600 px-2 py-1 rounded-lg text-xs font-bold shrink-0">
                    ⭐ {{ rating?.toFixed(1) || '5.0' }}
                </div>
            </div>

            <div v-if="discount" class="mt-2.5">
                <span
                    class="inline-flex items-center text-[11px] font-medium text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100/60">
                    🏷️ Giảm {{ discount }}% từ ngày thứ 3
                </span>
            </div>

            <div class="grid grid-cols-3 gap-1 mt-4 bg-slate-50 p-2 rounded-xl text-center">
                <div class="flex flex-col items-center justify-center py-1 border-r border-slate-200/60">
                    <span class="text-xs font-semibold text-slate-700">{{ seats }} chỗ</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Sức chứa</span>
                </div>
                <div class="flex flex-col items-center justify-center py-1 border-r border-slate-200/60">
                    <span class="text-xs font-semibold text-slate-700 truncate max-w-full px-1">{{ transmission
                        }}</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Hộp số</span>
                </div>
                <div class="flex flex-col items-center justify-center py-1">
                    <span class="text-xs font-semibold text-slate-700">{{ fuel }}</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Nhiên liệu</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-auto pt-4 border-t border-slate-100">
                <div class="text-xs">
                    <p class="text-slate-400">Đã chạy</p>
                    <p class="font-bold text-slate-700 mt-0.5">
                        {{ trips }} chuyến
                    </p>
                </div>

                <button
                    class="bg-[#286874] text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-[#1f4f58] shadow-sm shadow-[#286874]/10 active:scale-95 transition-all">
                    Đặt xe ngay
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
// Mở rộng thêm các Props thực tế của ngành Rent-car để card trông đầy đủ thông tin hơn
defineProps({
    name: { type: String, required: true },
    image: { type: String, required: true },
    price: { type: [String, Number], required: true },
    location: { type: String, default: 'Chưa cập nhật' },
    seats: { type: Number, default: 4 },
    transmission: { type: String, default: 'Số tự động' },
    fuel: { type: String, default: 'Xăng' },
    rating: { type: Number, default: 5.0 },
    trips: { type: Number, default: 0 },

    // Thêm các tính năng mới giúp tối ưu hóa UI/UX
    isInstantBook: { type: Boolean, default: false },
    isDelivery: { type: Boolean, default: false },
    discount: { type: Number, default: 0 }
})

// Hàm format nhanh tiền tệ (Ví dụ: truyền vào số 800000 -> xuất ra 800.000đ)
const formatPrice = (val: string | number) => {
    if (typeof val === 'number') {
        return val.toLocaleString('vi-VN') + 'đ';
    }
    return val;
}
</script>