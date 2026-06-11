<template>
    <div
        class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex items-center justify-between gap-3 select-none">

        <div class="flex-grow flex items-center gap-2 overflow-x-auto overflow-y-hidden py-1 no-scrollbar flex-nowrap">

            <div class="flex-shrink-0">
                <button @click="openModal('seats')" type="button"
                    :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-2', selectedSeats.length > 0 ? 'border-brand-primary bg-brand-primary/5 text-brand-primary' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                    <span>🚗 Loại xe</span>
                    <span v-if="selectedSeats.length > 0"
                        class="bg-brand-primary text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">{{
                        selectedSeats.length }}</span>
                </button>
            </div>

            <div class="flex-shrink-0">
                <button @click="openModal('brands')" type="button"
                    :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-2 flex-shrink-0', selectedBrands.length > 0 ? 'border-brand-primary bg-brand-primary/5 text-brand-primary' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                    <span>✨ Hãng xe</span>
                    <span v-if="selectedBrands.length > 0"
                        class="bg-brand-primary text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">{{
                        selectedBrands.length }}</span>
                </button>
            </div>

            <button @click="isHourlyRent = !isHourlyRent" type="button"
                :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 flex-shrink-0', isHourlyRent ? 'border-brand-primary bg-brand-primary/5 text-brand-primary shadow-sm font-bold' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                <span>⏱️ Thuê giờ</span>
            </button>

            <button @click="deliveryToHome = !deliveryToHome" type="button"
                :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 flex-shrink-0', deliveryToHome ? 'border-brand-primary bg-brand-primary/5 text-brand-primary shadow-sm font-bold' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                <span>📍 Giao tận nơi</span>
            </button>

            <button @click="isFiveStarHost = !isFiveStarHost" type="button"
                :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 flex-shrink-0', isFiveStarHost ? 'border-brand-primary bg-brand-primary/5 text-brand-primary shadow-sm font-bold' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                <span>🏅 Chủ xe 5★</span>
            </button>

            <button @click="instantBook = !instantBook" type="button"
                :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 flex-shrink-0', instantBook ? 'border-amber-500 bg-amber-50 text-amber-700 shadow-sm font-bold' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                <span>⚡ Đặt nhanh</span>
            </button>

            <button @click="noDeposit = !noDeposit" type="button"
                :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 flex-shrink-0', noDeposit ? 'border-brand-primary bg-brand-primary/5 text-brand-primary shadow-sm font-bold' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                <span>🪪 Miễn thế chấp</span>
            </button>

            <button @click="hasDiscount = !hasDiscount" type="button"
                :class="['px-4 py-2 text-xs font-semibold rounded-xl border transition-all flex items-center gap-1 flex-shrink-0 mr-2', hasDiscount ? 'border-brand-primary bg-brand-primary/5 text-brand-primary shadow-sm font-bold' : 'border-slate-200 text-slate-700 bg-slate-50/50 hover:bg-slate-50']">
                <span>🉐 Giảm giá</span>
            </button>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0 border-l border-slate-200 pl-4">

            <button @click="resetFilters" type="button" :disabled="!isFiltering" :class="[
                'text-xs font-bold transition-all flex items-center gap-1 py-2',
                isFiltering
                    ? 'text-slate-800 hover:text-rose-500 cursor-pointer opacity-100'
                    : 'text-slate-300 cursor-not-allowed opacity-60'
            ]">
                <span>Xóa lọc</span>
            </button>

            <button @click="openModal('advanced')" type="button"
                :class="['px-4 py-2 text-xs font-bold rounded-xl border transition-all flex items-center gap-2 shadow-sm', isAnyAdvancedFilterActive ? 'border-brand-primary bg-brand-primary text-white' : 'border-slate-200 text-slate-800 bg-white hover:bg-slate-50']">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Bộ lọc</span>
            </button>
        </div>

        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="activeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div @click="closeModal" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" />

                    <div
                        class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] modal-content">
                        <div
                            class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">{{
                                modalTitles[activeModal] }}</h4>
                            <button @click="closeModal"
                                class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600"><svg
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg></button>
                        </div>

                        <div class="p-5 overflow-y-auto custom-scrollbar flex-1">

                            <div v-if="activeModal === 'seats'" class="space-y-2.5">
                                <label v-for="seat in seatOptions" :key="seat.value"
                                    class="flex items-center gap-3 cursor-pointer p-3 border border-slate-100 rounded-xl hover:bg-slate-50/80 text-slate-700 font-semibold text-xs">
                                    <input type="checkbox" :value="seat.value" v-model="selectedSeats"
                                        class="rounded text-brand-primary border-slate-300 w-4 h-4 focus:ring-0" />
                                    <span>{{ seat.label }}</span>
                                </label>
                            </div>

                            <div v-if="activeModal === 'brands'" class="grid grid-cols-2 gap-2">
                                <label v-for="brand in brandOptions" :key="brand"
                                    class="flex items-center gap-2.5 cursor-pointer p-2.5 border border-slate-100 rounded-xl hover:bg-slate-50/80 text-slate-700 font-semibold text-xs">
                                    <input type="checkbox" :value="brand" v-model="selectedBrands"
                                        class="rounded text-brand-primary border-slate-300 w-4 h-4 focus:ring-0" />
                                    <span class="truncate">{{ brand }}</span>
                                </label>
                            </div>

                            <div v-if="activeModal === 'advanced'" class="space-y-4 text-slate-700">
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 block mb-1.5 uppercase tracking-wider">Giá
                                        thuê (1 ngày)</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input v-model.number="priceMin" type="number" placeholder="Từ"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-primary" />
                                        <input v-model.number="priceMax" type="number" placeholder="Đến"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-brand-primary" />
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 block mb-1.5 uppercase tracking-wider">Hộp
                                        số</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button @click="transmission = transmission === 'auto' ? '' : 'auto'"
                                            type="button"
                                            :class="['py-2 text-xs font-semibold rounded-xl border transition-all', transmission === 'auto' ? 'border-brand-primary bg-brand-primary/5 text-brand-primary' : 'border-slate-200 text-slate-600 bg-slate-50/50']">Tự
                                            động</button>
                                        <button @click="transmission = transmission === 'manual' ? '' : 'manual'"
                                            type="button"
                                            :class="['py-2 text-xs font-semibold rounded-xl border transition-all', transmission === 'manual' ? 'border-brand-primary bg-brand-primary/5 text-brand-primary' : 'border-slate-200 text-slate-600 bg-slate-50/50']">Số
                                            sàn</button>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 block mb-1.5 uppercase tracking-wider">Nhiên
                                        liệu</label>
                                    <select v-model="fuelType"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-brand-primary">
                                        <option value="">Tất cả</option>
                                        <option value="gas">Xăng</option>
                                        <option value="diesel">Dầu Diesel</option>
                                        <option value="electric">Điện (EV)</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 block mb-2 uppercase tracking-wider">Tiện
                                        nghi trên xe</label>
                                    <div class="max-h-44 overflow-y-auto grid grid-cols-2 gap-2 pr-1 custom-scrollbar">
                                        <label v-for="feat in featureOptions" :key="feat.value"
                                            class="flex items-center gap-2.5 cursor-pointer p-2 border border-slate-100 rounded-xl hover:bg-slate-50 text-slate-600 font-semibold text-[11px]">
                                            <input type="checkbox" :value="feat.value" v-model="selectedFeatures"
                                                class="rounded text-brand-primary border-slate-300 w-3.5 h-3.5 focus:ring-0" />
                                            <span class="truncate">{{ feat.label }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div
                            class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3 flex-shrink-0">
                            <button @click="closeModal" type="button"
                                class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Đóng</button>
                            <button @click="applyFilters" type="button"
                                class="px-5 py-2 bg-brand-primary text-white font-bold text-xs rounded-xl shadow-md shadow-brand-primary/10 hover:bg-opacity-95">Áp
                                dụng</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'

const activeModal = ref('')

// Trạng thái các bộ lọc chính
const selectedSeats = ref<string[]>([])
const selectedBrands = ref<string[]>([])
const isHourlyRent = ref(false)
const deliveryToHome = ref(false)
const isFiveStarHost = ref(false)
const instantBook = ref(false)
const noDeposit = ref(false)
const hasDiscount = ref(false)

// Ruột nâng cao nâng cấp trong nút Bộ Lọc
const priceMin = ref(0)
const priceMax = ref(5000000)
const transmission = ref('')
const fuelType = ref('')
const selectedFeatures = ref<string[]>([])

// 🎯 LOGIC TỰ ĐỘNG PHÁT HIỆN SỰ THAY ĐỔI ĐỂ LÀM SÁNG NÚT XÓA LỌC
const isFiltering = computed(() => {
    return (
        selectedSeats.value.length > 0 ||
        selectedBrands.value.length > 0 ||
        isHourlyRent.value ||
        deliveryToHome.value ||
        isFiveStarHost.value ||
        instantBook.value ||
        noDeposit.value ||
        hasDiscount.value ||
        priceMin.value > 0 ||
        priceMax.value < 5000000 ||
        transmission.value !== '' ||
        fuelType.value !== '' ||
        selectedFeatures.value.length > 0
    )
})

const modalTitles: Record<string, string> = {
    seats: 'Chọn số chỗ ngồi',
    brands: 'Chọn hãng xe sản xuất',
    advanced: 'Tất cả bộ lọc nâng cao'
}

const seatOptions = [
    { label: 'Xe 4 chỗ', value: '4' },
    { label: 'Xe 5 chỗ', value: '5' },
    { label: 'Xe 7 chỗ', value: '7' },
    { label: 'Xe Bán tải', value: 'pickup' }
]

const brandOptions = ['VinFast', 'Toyota', 'Hyundai', 'Kia', 'Honda', 'Mitsubishi', 'Mazda', 'Ford', 'Suzuki', 'Nissan']

const featureOptions = [
    { label: '🗺️ Bản đồ / GPS', value: 'gps' },
    { label: '📷 Camera lùi', value: 'camera_rear' },
    { label: '📸 Camera 360', value: 'camera_360' },
    { label: '☀️ Cửa sổ trời', value: 'sunroof' },
    { label: '🔌 Cổng sạc USB', value: 'usb' },
    { label: '📶 Bluetooth', value: 'bluetooth' },
    { label: '👶 Ghế trẻ em', value: 'baby_seat' },
    { label: '💳 Thẻ ETC', value: 'etc' }
]

const isAnyAdvancedFilterActive = computed(() => {
    return priceMin.value > 0 || priceMax.value < 5000000 || !!transmission.value || !!fuelType.value || selectedFeatures.value.length > 0
})

const openModal = (name: string) => { activeModal.value = name }
const closeModal = () => { activeModal.value = '' }

const applyFilters = () => {
    closeModal()
    console.log('Gửi dữ liệu lọc:', {
        seats: selectedSeats.value, brands: selectedBrands.value, hourly: isHourlyRent.value,
        delivery: deliveryToHome.value, fiveStar: isFiveStarHost.value, instant: instantBook.value,
        noDeposit: noDeposit.value, discount: hasDiscount.value, priceMin: priceMin.value,
        priceMax: priceMax.value, transmission: transmission.value, fuel: fuelType.value, features: selectedFeatures.value
    })
}

const resetFilters = () => {
    if (!isFiltering.value) return // Nếu chưa lọc gì thì không cho chạy
    closeModal()
    selectedSeats.value = []
    selectedBrands.value = []
    isHourlyRent.value = false
    deliveryToHome.value = false
    isFiveStarHost.value = false
    instantBook.value = false
    noDeposit.value = false
    hasDiscount.value = false
    priceMin.value = 0
    priceMax.value = 5000000
    transmission.value = ''
    fuelType.value = ''
    selectedFeatures.value = []
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* ANIMATION TRANSITION */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-fade-enter-active .modal-content,
.modal-fade-leave-active .modal-content {
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

.modal-fade-enter-from .modal-content,
.modal-fade-leave-to .modal-content {
    opacity: 0;
    transform: scale(0.94) translateY(10px);
}
</style>