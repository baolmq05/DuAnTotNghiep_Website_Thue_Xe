<template>
    <div class="space-y-6 min-h-screen">
        <!-- Page Header -->
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900">Chuyến của tôi</h1>
            <p class="text-sm text-slate-500">Quản lý tất cả các chuyến đặt xe và chuyến cho thuê của bạn.</p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-1 rounded-2xl border border-slate-200 bg-slate-100/70 p-1 w-fit">
            <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200"
                :class="activeTab === tab.key
                    ? 'bg-white text-[#1e4e57] shadow-sm shadow-slate-200'
                    : 'text-slate-500 hover:text-slate-700'">
                <Icon :name="tab.icon" class="w-4 h-4" />
                {{ tab.label }}
                <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-[10px] font-black"
                    :class="activeTab === tab.key ? 'bg-[#1e4e57]/10 text-[#1e4e57]' : 'bg-slate-200 text-slate-500'">
                    {{ tab.key === 'booked' ? bookedTrips.length : ownerTrips.length }}
                </span>
            </button>
        </div>
        <!-- Filter Bar -->
        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <!-- Row 1: Search -->
            <div class="relative">
                <Icon
                    name="lucide:search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                <input v-model="searchQuery" type="text" placeholder="Tìm theo tên xe, biển số..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10" />
            </div>

            <!-- Row 2: Filters -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Status Filter -->
                <div class="relative flex-1">
                    <select v-model="filterStatus"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="">Tất cả trạng thái</option>
                        <option v-for="opt in TripStatusOptions" :key="opt.value" :value="opt.value">
                            {{ opt.label }}
                        </option>
                    </select>
                    <Icon
                        name="lucide:chevron-down" class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                </div>

                <!-- Trip Type Filter -->
                <div class="relative flex-1">
                    <select v-model="filterType"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="">Tất cả loại thuê</option>
                        <option value="0">Thuê theo ngày</option>
                        <option value="1">Thuê theo km</option>
                    </select>
                    <Icon
                        name="lucide:chevron-down" class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                </div>

                <!-- Sort -->
                <div class="relative flex-1">
                    <select v-model="sortOrder"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="newest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="cost_asc">Giá tăng dần</option>
                        <option value="cost_desc">Giá giảm dần</option>
                    </select>
                    <Icon
                        name="lucide:chevron-down" class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                </div>

                <!-- Clear filters button -->
                <button v-if="hasActiveFilters" @click="clearFilters"
                    class="flex items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100 shrink-0">
                    <Icon name="lucide:x" class="w-3.5 h-3.5" />
                    Xóa lọc
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="rounded-2xl border border-slate-200 bg-white py-16 text-center shadow-sm">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#1e4e57] mx-auto mb-3"></div>
            <p class="text-sm text-slate-500 font-medium">Đang tải danh sách chuyến đi...</p>
        </div>

        <template v-else>
            <!-- BOOKED TRIPS TAB -->
            <div v-if="activeTab === 'booked'"
                class="space-y-4 max-lg:space-y-0 max-lg:grid max-lg:gap-5 md:grid-cols-2">
                <p v-if="filteredBookedTrips.length === 0"
                    class="rounded-2xl border border-dashed border-slate-200 py-16 text-center text-sm text-slate-400">
                    <Icon name="lucide:calendar-off" class="mb-3 mx-auto block w-8 h-8 text-slate-300" />
                    Không tìm thấy chuyến nào phù hợp với bộ lọc.
                </p>

                <div v-for="trip in filteredBookedTrips" :key="trip.id"
                    class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:border-[#1e4e57]/30 hover:shadow-md">
                    <div class="flex lg:flex-row max-lg:flex-col">
                        <!-- Car Image: fixed left column -->
                        <div class="relative shrink-0 overflow-hidden max-lg:!w-full max-lg:!h-52 md:max-lg:!h-48"
                            style="width: 280px; min-height: 160px;">
                            <img :src="trip.car.image" :alt="trip.car.name"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            <!-- Trip type badge -->
                            <span
                                class="absolute left-3 top-3 rounded-lg px-3 py-1.5 text-xs font-bold text-white shadow-md shadow-black/20 bg-[#1e4e57] flex items-center gap-1.5">
                                <Icon :name="trip.trip_type === 0 ? 'lucide:calendar' : 'lucide:route'"
                                    class="w-3.5 h-3.5" />
                                {{ trip.trip_type === 0 ? 'Thuê theo ngày' : 'Thuê theo km' }}
                            </span>
                        </div>

                        <!-- Content: right side -->
                        <div class="flex flex-1 flex-col justify-between p-5 max-sm:p-4 bg-white">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-bold text-slate-900 text-base max-sm:text-sm">{{ trip.car.name
                                        }}
                                        </h3>
                                        <span
                                            class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">{{
                                                trip.car.license_plate }}</span>
                                    </div>
                                </div>

                                <!-- Badges Container -->
                                <div class="flex flex-wrap items-center gap-2 mb-1 shrink-0">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs max-sm:text-[9px] font-bold"
                                        :class="statusClass(trip.status)">
                                        {{ statusLabel(trip.status) }}
                                    </span>
                                    <span v-if="trip.latest_extension"
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs max-sm:text-[9px] font-bold border"
                                        :class="extensionStatusClass(trip.latest_extension.status)">
                                        {{ extensionStatusLabel(trip.latest_extension.status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Time grid -->
                            <div class="flex max-sm:grid max-sm:grid-cols-2 gap-2">
                                <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                    <p
                                        class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                        <Icon name="lucide:clock" class="w-3 h-3" />Bắt đầu
                                    </p>
                                    <p class="text-sm max-sm:text-xs font-semibold text-slate-700">{{
                                        formatDate(trip.start_at) }}</p>
                                </div>
                                <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                    <p
                                        class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                        <Icon name="lucide:flag" class="w-3 h-3" />Kết thúc
                                    </p>
                                    <p class="text-sm max-sm:text-xs font-semibold text-slate-700">{{
                                        formatDate(trip.end_at) }}</p>
                                </div>
                                <div
                                    class="flex max-sm:col-span-2 shrink-0 items-center justify-center rounded-lg bg-slate-50 px-4 py-2">
                                    <p class="text-sm max-sm:text-xs font-semibold text-slate-700 whitespace-nowrap">{{
                                        duration(trip.start_at, trip.end_at) }}</p>
                                </div>
                            </div>

                            <!-- Footer row -->
                            <div class="mt-3 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <p class="text-xs text-slate-400">Tổng chi phí</p>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-xl font-black text-[#1e4e57]">{{ formatCurrency(trip.cost -
                                            trip.discount_amount) }}</span>
                                        <span v-if="trip.discount_amount > 0" class="text-xs text-slate-400"
                                            style="text-decoration-line: line-through;">
                                            {{ formatCurrency(trip.cost) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button @click="navigateTo('/trips/' + trip.id)"
                                        class="rounded-xl bg-[#1e4e57] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#286874]">
                                        Chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { TripStatus, TripStatusLabel, TripStatusBadgeClass, TripStatusOptions } from '~/config/trip-status'

definePageMeta({
    layout: 'profile',
})

// ─── Types ────────────────────────────────────────────────────────────────────
interface Car {
    id: number
    name: string
    license_plate: string
    fuel_consumption: number
    unit_price: number
    discount_value: number
    description: string | null
    rental_terms: string | null
    car_location_id: number
    car_brand_id: number
    car_type_id: number
    seat_count: number
    manufacture_year: string
    fuel_type: string
    transmission: string
    user_id: number
    delivery_option_id: number
    usage_limit_id: number
    // UI extras
    image: string
    location: string
}

interface Trip {
    id: number
    cost: number
    discount_amount: number
    status: number   // 0: Chờ duyệt | 1: Chờ thanh toán | 2: Đã xác nhận | 3: Đang diễn ra | 4: Đã hoàn thành | 5: Người dùng hủy | 6: Chủ xe hủy
    trip_type: number // 0: theo ngày | 1: theo km
    start_at: string
    end_at: string
    car_id: number
    user_id: number
    // Joined
    car: Car
    renter?: { name: string; phone: string }
    latest_extension?: any
}

// ─── Tabs ─────────────────────────────────────────────────────────────────────
const tabs = [
    { key: 'booked', label: 'Chuyến đã đặt', icon: 'lucide:car' },
] as const
const activeTab = ref<'booked' | 'owner'>('booked')

// ─── Filters ──────────────────────────────────────────────────────────────────
const searchQuery = ref('')
const filterStatus = ref('')
const filterType = ref('')
const sortOrder = ref('newest')

const hasActiveFilters = computed(() =>
    searchQuery.value !== '' || filterStatus.value !== '' || filterType.value !== '' || sortOrder.value !== 'newest'
)

function clearFilters() {
    searchQuery.value = ''
    filterStatus.value = ''
    filterType.value = ''
    sortOrder.value = 'newest'
}

// ─── Real Data ────────────────────────────────────────────────────────────────
const bookedTrips = ref<Trip[]>([])
const ownerTrips = ref<Trip[]>([])
const loading = ref(true)

import { carService } from '~/services/car.service'

const loadTrips = async () => {
    loading.value = true
    try {
        const res = await carService.getTrips()
        if (res && res.success && res.data) {
            // Normalize booked trips
            bookedTrips.value = res.data.booked.map((trip: any) => {
                const thumbnailImg = trip.car?.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
                    || trip.car?.images?.[0]?.image_url
                    || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';

                return {
                    ...trip,
                    car: {
                        ...trip.car,
                        image: thumbnailImg,
                        location: trip.car?.car_location?.address || 'Chưa cập nhật'
                    }
                }
            })

            // Normalize owner trips
            ownerTrips.value = res.data.owner.map((trip: any) => {
                const thumbnailImg = trip.car?.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
                    || trip.car?.images?.[0]?.image_url
                    || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';

                return {
                    ...trip,
                    car: {
                        ...trip.car,
                        image: thumbnailImg,
                        location: trip.car?.car_location?.address || 'Chưa cập nhật'
                    },
                    renter: {
                        name: trip.user?.name || 'Khách hàng',
                        phone: trip.user?.phone || 'Chưa cập nhật SĐT'
                    }
                }
            })
        }
    } catch (e) {
        console.error('Không tải được danh sách chuyến đi:', e)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadTrips()
})

// ─── Helpers ──────────────────────────────────────────────────────────────────
function applyFilters(list: Trip[]): Trip[] {
    let result = [...list]

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(t =>
            t.car.name.toLowerCase().includes(q) ||
            t.car.license_plate.toLowerCase().includes(q)
        )
    }
    if (filterStatus.value !== '') {
        result = result.filter(t => t.status === Number(filterStatus.value))
    }
    if (filterType.value !== '') {
        result = result.filter(t => t.trip_type === Number(filterType.value))
    }
    if (sortOrder.value === 'newest') result.sort((a, b) => new Date(b.start_at).getTime() - new Date(a.start_at).getTime())
    if (sortOrder.value === 'oldest') result.sort((a, b) => new Date(a.start_at).getTime() - new Date(b.start_at).getTime())
    if (sortOrder.value === 'cost_asc') result.sort((a, b) => (a.cost - a.discount_amount) - (b.cost - b.discount_amount))
    if (sortOrder.value === 'cost_desc') result.sort((a, b) => (b.cost - b.discount_amount) - (a.cost - a.discount_amount))

    return result
}

const filteredBookedTrips = computed(() => applyFilters(bookedTrips.value))
const filteredOwnerTrips = computed(() => applyFilters(ownerTrips.value))

function statusLabel(status: any) {
    return TripStatusLabel[Number(status) as TripStatus] ?? '—'
}

function statusClass(status: any) {
    return TripStatusBadgeClass[Number(status) as TripStatus] ?? 'bg-slate-100 text-slate-500'
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

function extensionStatusLabel(status?: number) {
    switch (status) {
        case 1: return 'Gia hạn: Chờ duyệt'
        case 2: return 'Gia hạn: Chờ thanh toán'
        case 3: return 'Gia hạn: Thành công'
        case 4: return 'Gia hạn: Bị từ chối'
        default: return ''
    }
}

function extensionStatusClass(status?: number) {
    switch (status) {
        case 1: return 'bg-indigo-50 border-indigo-200 text-indigo-700'
        case 2: return 'bg-amber-50 border-amber-200 text-amber-700'
        case 3: return 'bg-emerald-50 border-emerald-200 text-emerald-700'
        case 4: return 'bg-rose-50 border-rose-200 text-rose-700'
        default: return 'bg-slate-100 border-slate-200 text-slate-600'
    }
}
</script>