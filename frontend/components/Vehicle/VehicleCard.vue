<template>
    <div
        class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full transform translate-z-0">
        <!-- Khu vực hình ảnh -->
        <div class="relative overflow-hidden aspect-[16/10.5] shrink-0">
            <img :src="image" :alt="name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

            <!-- Gradient phủ nhẹ lên ảnh -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>

            <!-- GÓC TRÊN TRÁI: Chỉ giữ lại nhãn Giảm giá -->
            <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                <span v-if="discount > 0"
                    class="bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm uppercase tracking-wider flex items-center gap-1">
                    <Icon name="lucide:tag" class="w-3 h-3" /> Giảm {{ discount }}%
                </span>
            </div>

            <!-- GÓC TRÊN PHẢI: Nút yêu thích TRẦN (Bỏ vòng tròn) -->
            <button @click.stop="$emit('toggle-favorite')"
                class="absolute top-3 right-3 z-10 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)] hover:text-rose-500 transition-colors duration-200 active:scale-90">
                <Icon name="heroicons:heart" class="w-6 h-6" />
            </button>

            <!-- GÓC DƯỚI PHẢI TRÊN ẢNH: Avatar chủ xe -->
            <div
                class="absolute bottom-2.5 right-2.5 z-10 flex items-center gap-1.5 bg-slate-900/40 backdrop-blur-md rounded-full pl-1 pr-2.5 py-0.5 border border-white/10 text-white">
                <img :src="ownerAvatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100'"
                    alt="Owner Avatar" class="w-6 h-6 rounded-full object-cover border border-white/20 shrink-0" />
                <span class="text-[11px] font-medium truncate max-w-[80px]">{{ ownerName }}</span>
            </div>
        </div>

        <!-- Khu vực thông tin chi tiết -->
        <div class="p-3.5 flex flex-col flex-grow justify-between">
            <div class="flex flex-col flex-grow justify-start">

                <!-- PHÍA TRÊN TÊN XE: Thêm 2 nhãn Miễn thế chấp & Giao xe tận nơi -->
                <!-- PHÍA TRÊN TÊN XE: Các trường dữ liệu tính năng -->
                <div class="flex flex-wrap gap-1.5 mb-1.5 min-h-[20px]">
                    <span v-if="noDeposit"
                        class="inline-flex items-center text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                        Miễn thế chấp
                    </span>
                    <span v-if="isDelivery"
                        class="inline-flex items-center text-[10px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">
                        Giao xe tận nơi
                    </span>
                </div>

                <div class="flex justify-between items-start gap-2">
                    <div class="flex-grow min-w-0">
                        <h3
                            class="font-bold text-base text-slate-800 line-clamp-1 group-hover:text-[#286874] transition-colors">
                            {{ name }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                            <Icon name="lucide:map-pin" class="shrink-0" /> <span class="truncate">{{ location }}</span>
                        </p>
                    </div>

                    <!-- Điểm đánh giá xe -->
                    <div
                        class="flex items-center gap-0.5 bg-amber-50 text-amber-600 px-2 py-0.5 rounded-lg text-xs font-bold shrink-0 mt-0.5">
                        <Icon name="heroicons:star-solid" /> {{ rating?.toFixed(1) || '5.0' }}
                    </div>
                </div>
            </div>

            <!-- Thu nhỏ khoảng cách trước khối thông số -->
            <div class="mt-1">
                <!-- Thông số xe -->
                <div class="grid grid-cols-3 gap-1 bg-slate-50 p-2 rounded-xl text-center">
                    <div class="flex flex-col items-center justify-center py-0.5 border-r border-slate-200/60">
                        <span class="text-xs font-semibold text-slate-700">{{ seats }} chỗ</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Sức chứa</span>
                    </div>
                    <div class="flex flex-col items-center justify-center py-0.5 border-r border-slate-200/60 min-w-0">
                        <span class="text-xs font-semibold text-slate-700 truncate w-full px-1">{{ transmission
                        }}</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Hộp số</span>
                    </div>
                    <div class="flex flex-col items-center justify-center py-0.5 min-w-0">
                        <span class="text-xs font-semibold text-slate-700 truncate w-full px-1">{{ fuel }}</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Nhiên liệu</span>
                    </div>
                </div>

                <!-- FOOTER: Đã chạy & Giá tiền (Thu hẹp khoảng cách mt-3 pt-3) -->
                <div class="flex justify-between items-end mt-3 pt-3 border-t border-slate-100">
                    <!-- Bên trái: Số chuyến đi -->
                    <div class="text-xs">
                        <p class="text-slate-400">Đã chạy</p>
                        <p class="font-bold text-slate-700 mt-0.5">
                            {{ trips }} chuyến
                        </p>
                    </div>

                    <!-- Bên phải: Giá tiền -->
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Giá từ</p>
                        <p class="font-extrabold text-lg text-[#286874] leading-none">
                            {{ formatPrice(price) }}
                            <span class="text-xs text-slate-500 font-normal">/ngày</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
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
    discount: { type: Number, default: 0 },

    // Thông tin chủ xe hiển thị góc dưới ảnh
    ownerName: { type: String, default: 'Chủ xe' },
    ownerAvatar: { type: String, default: '' },

    noDeposit: { type: Boolean, default: false },     // Miễn thế chấp / Không cọc tài sản
    isDelivery: { type: Boolean, default: false }   // Giao xe tận nơi / Không cần đến điểm nhận xe
})

defineEmits(['toggle-favorite'])

const formatPrice = (val: string | number) => {
    if (typeof val === 'number') {
        return val.toLocaleString('vi-VN') + 'đ';
    }
    return val;
}
</script>