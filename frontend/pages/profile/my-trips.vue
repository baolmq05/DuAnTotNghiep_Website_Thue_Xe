<template>
    <div class="space-y-6 min-h-screen">
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 text-center">Chuyến của tôi</h1>
            <p class="text-sm text-slate-500 text-center">
                Quản lý tất cả các chuyến đặt xe của bạn
            </p>
        </div>

        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="relative">
                <i
                    class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input v-model="filter.search" type="text" placeholder="Tìm theo tên xe, biển số..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10" />
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4 sm:items-center">
                <div class="relative">
                    <select v-model="filter.status"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="">Tất cả trạng thái</option>
                        <option value="0">Chưa bắt đầu</option>
                        <option value="1">Đang diễn ra</option>
                        <option value="2">Đã hoàn thành</option>
                        <option value="3">Đã hủy bởi bạn</option>
                        <option value="4">Đã hủy bởi chủ xe</option>
                    </select>
                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                </div>

                <div class="relative">
                    <select v-model="filter.trip_type"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="">Tất cả loại thuê</option>
                        <option value="0">Thuê theo ngày</option>
                        <option value="1">Thuê theo km</option>
                    </select>
                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                </div>

                <div class="relative">
                    <select v-model="filter.sort_by"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="latest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="price_asc">Giá tăng dần</option>
                        <option value="price_desc">Giá giảm dần</option>
                    </select>
                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                </div>

                <button @click="clearFilters" :disabled="!hasActiveFilters"
                    class="flex items-center justify-center gap-1.5 rounded-xl border py-2.5 px-4 text-sm font-semibold transition-all duration-200 w-full"
                    :class="hasActiveFilters
                        ? 'border-red-200 bg-red-50 text-red-600 shadow-sm hover:bg-red-100 cursor-pointer active:scale-95'
                        : 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed opacity-60'">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    Xóa lọc
                </button>
            </div>
        </div>

        <div class="space-y-4 max-lg:space-y-0 max-lg:grid max-lg:gap-5 md:grid-cols-2">

            <div v-if="isLoading"
                class="w-full flex flex-col items-center justify-center py-24 text-center text-slate-500 mx-auto md:col-span-2">
                <i class="fa-solid fa-circle-notch animate-spin text-3xl text-[#1e4e57] mb-3"></i>
                <p class="text-sm font-medium tracking-wide">Đang tải danh sách chuyến đi...</p>
            </div>

            <template v-else>
                <p v-if="bookedTrips.length === 0"
                    class="rounded-2xl border border-dashed border-slate-200 py-16 text-center text-sm text-slate-400 md:col-span-2">
                    <i class="fa-regular fa-calendar-xmark mb-3 block text-3xl text-slate-300"></i>
                    Không tìm thấy chuyến nào phù hợp với bộ lọc.
                </p>

                <div v-for="trip in bookedTrips" :key="trip.id"
                    class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:border-[#1e4e57]/30 hover:shadow-md">
                    <div class="flex lg:flex-row max-lg:flex-col">
                        <div class="relative shrink-0 overflow-hidden max-lg:!w-full max-lg:!h-52 md:max-lg:!h-48"
                            style="width: 280px; min-height: 160px;">
                            <img :src="trip.car.image || 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80'"
                                :alt="trip.car.name"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            <span
                                class="absolute left-3 top-3 rounded-lg px-3 py-1.5 text-xs font-bold text-white shadow-md shadow-black/20 bg-[#1e4e57]">
                                <i :class="trip.trip_type === 0 ? 'fa-solid fa-calendar-days' : 'fa-solid fa-road'"
                                    class="mr-1.5"></i>
                                {{ trip.trip_type_text }}
                            </span>
                        </div>

                        <div class="flex flex-1 flex-col justify-between p-5 max-sm:p-4 bg-white">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-bold text-slate-900 text-base max-sm:text-sm">{{ trip.car.name
                                        }}
                                        </h3>
                                        <span
                                            class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">
                                            {{ trip.car.license_plate }}
                                        </span>
                                    </div>
                                </div>

                                <span
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs max-sm:text-[11px] font-bold"
                                    :class="statusClass(trip.status)">
                                    <!-- <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(trip.status)"></span> -->
                                    {{ trip.status_text }}
                                </span>
                            </div>

                            <div class="flex max-sm:grid max-sm:grid-cols-2 gap-2 mt-4">
                                <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                    <p
                                        class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                        <i class="fa-regular fa-clock"></i>Bắt đầu
                                    </p>
                                    <p class="text-sm max-sm:text-xs font-semibold text-slate-700 whitespace-nowrap">
                                        {{ formatDate(trip.start_at) }}
                                    </p>
                                </div>

                                <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                    <p
                                        class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                        <i class="fa-regular fa-flag"></i>Kết thúc
                                    </p>
                                    <p class="text-sm max-sm:text-xs font-semibold text-slate-700 whitespace-nowrap">
                                        {{ formatDate(trip.end_at) }}
                                    </p>
                                </div>

                                <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                    <p
                                        class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                        <i class="fa-regular fa-flag"></i>Thời gian
                                    </p>
                                    <p class="text-sm max-sm:text-xs font-semibold text-slate-700 whitespace-nowrap">
                                        {{ duration(trip.start_at, trip.end_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <p class="text-xs text-slate-400">Tổng chi phí</p>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-xl font-black text-[#1e4e57]">
                                            {{ formatCurrency(Number(trip.cost) - Number(trip.discount_amount)) }}
                                        </span>
                                        <span v-if="Number(trip.discount_amount) > 0"
                                            class="text-xs text-slate-400 inline-block"
                                            style="text-decoration-line: line-through;">
                                            {{ formatCurrency(Number(trip.cost)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'

definePageMeta({
    layout: 'profile',
})

// khai báo cấu hình và token
const config = useRuntimeConfig();
const baseApi = config.public.apiBase || 'http://127.0.0.1:8000/api';
const token = useCookie('USER_TOKEN').value || '';

if (!token && process.client) {
    useRouter().push('/');
}

// định nữa interface cho xe và trip
interface Car {
    id: number
    name: string
    license_plate: string
    image: string
}

interface Trip {
    id: number
    cost: number | string
    discount_amount: number | string
    status: number
    trip_type: number
    start_at: string
    end_at: string
    car: Car
    status_text?: string
    trip_type_text?: string
}

// Khai báo bộ lọc
const filter = reactive({
    search: '',
    status: '',
    trip_type: '',
    sort_by: 'latest'
})

// fetch dữ liệu chuyến đi với useLazyFetch, có watch theo filter và chỉ chạy khi có token
const { data: apiResponse, refresh, pending: isLoading } = await useLazyFetch<{ success: boolean, data: Trip[] }>(() => `${baseApi}/my-trips`, {
    params: filter,
    watch: [filter],
    server: false,
    immediate: !!token,
    headers: {
        Authorization: `Bearer ${token}`
    }
});

const bookedTrips = computed(() => {
    return apiResponse.value?.success ? apiResponse.value.data : []
});

const hasActiveFilters = computed(() =>
    filter.search !== '' || filter.status !== '' || filter.trip_type !== '' || filter.sort_by !== 'latest'
)

function clearFilters() {
    filter.search = ''
    filter.status = ''
    filter.trip_type = ''
    filter.sort_by = 'latest'
}

// các hàm phụ trợ để hiển thị trạng thái, định dạng ngày tháng, tính thời lượng và định dạng tiền tệ
function statusClass(status: number) {
    return [
        'bg-slate-100 text-slate-600',
        'bg-blue-50 text-blue-600',
        'bg-emerald-50 text-emerald-600',
        'bg-red-50 text-red-500',
        'bg-orange-50 text-orange-500',
    ][status] ?? 'bg-slate-100 text-slate-500'
}

function statusDot(status: number) {
    return [
        'bg-slate-400 animate-pulse',
        'bg-blue-500 animate-pulse',
        'bg-emerald-500',
        'bg-red-400',
        'bg-orange-400',
    ][status] ?? 'bg-slate-400'
}

function formatDate(dt: string) {
    return new Date(dt).toLocaleString('vi-VN', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    })
}

function duration(start: string, end: string) {
    const diff = new Date(end).getTime() - new Date(start).getTime()
    const days = Math.floor(diff / 86400000)
    const hours = Math.floor((diff % 86400000) / 3600000)
    return days > 0 ? `${days} ngày${hours > 0 ? ` ${hours} giờ` : ''}` : `${hours} giờ`
}

function formatCurrency(amount: number) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount)
}
</script>