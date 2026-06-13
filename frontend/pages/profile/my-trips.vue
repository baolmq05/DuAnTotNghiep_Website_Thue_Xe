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
                <i :class="tab.icon" class="text-xs"></i>
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
                <i
                    class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
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
                        <option value="0">Chưa bắt đầu</option>
                        <option value="1">Đang diễn ra</option>
                        <option value="2">Đã hoàn thành</option>
                        <option value="3">Đã hủy (bởi bạn)</option>
                        <option value="4">Đã hủy (bởi chủ xe)</option>
                    </select>
                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                </div>

                <!-- Trip Type Filter -->
                <div class="relative flex-1">
                    <select v-model="filterType"
                        class="w-full appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-9 text-sm text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10">
                        <option value="">Tất cả loại thuê</option>
                        <option value="0">Thuê theo ngày</option>
                        <option value="1">Thuê theo km</option>
                    </select>
                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
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
                    <i
                        class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                </div>

                <!-- Clear filters button -->
                <button v-if="hasActiveFilters" @click="clearFilters"
                    class="flex items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100 shrink-0">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    Xóa lọc
                </button>
            </div>
        </div>

        <!-- BOOKED TRIPS TAB -->
        <div v-if="activeTab === 'booked'" class="space-y-4 max-lg:space-y-0 max-lg:grid max-lg:gap-5 md:grid-cols-2">
            <p v-if="filteredBookedTrips.length === 0"
                class="rounded-2xl border border-dashed border-slate-200 py-16 text-center text-sm text-slate-400">
                <i class="fa-regular fa-calendar-xmark mb-3 block text-3xl text-slate-300"></i>
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
                            class="absolute left-3 top-3 rounded-lg px-3 py-1.5 text-xs font-bold text-white shadow-md shadow-black/20 bg-[#1e4e57]">
                            <i :class="trip.trip_type === 0 ? 'fa-solid fa-calendar-days' : 'fa-solid fa-road'"
                                class="mr-1.5"></i>
                            {{ trip.trip_type === 0 ? 'Thuê theo ngày' : 'Thuê theo km' }}
                        </span>
                    </div>

                    <!-- Content: right side -->
                    <div class="flex flex-1 flex-col justify-between p-5 max-sm:p-4 bg-white">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-bold text-slate-900 text-base max-sm:text-sm">{{ trip.car.name }}
                                    </h3>
                                    <span
                                        class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">{{
                                        trip.car.license_plate }}</span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <span
                                class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs max-sm:text-[11px] font-bold"
                                :class="statusClass(trip.status)">
                                <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(trip.status)"></span>
                                {{ statusLabel(trip.status) }}
                            </span>
                        </div>

                        <!-- Time grid -->
                        <div class="flex max-sm:grid max-sm:grid-cols-2 gap-2">
                            <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                <p class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                    <i class="fa-regular fa-clock"></i>Bắt đầu
                                </p>
                                <p class="text-sm max-sm:text-xs font-semibold text-slate-700">{{
                                    formatDate(trip.start_at) }}</p>
                            </div>
                            <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                                <p class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                    <i class="fa-regular fa-flag"></i>Kết thúc
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
                                <button
                                    class="rounded-xl bg-[#1e4e57] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#286874]">
                                    Chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OWNER TRIPS TAB -->
        <div v-if="activeTab === 'owner'" class="space-y-4 max-lg:space-y-0 max-lg:grid max-lg:gap-5 md:grid-cols-2">
            <p v-if="filteredOwnerTrips.length === 0"
                class="rounded-2xl border border-dashed border-slate-200 py-16 text-center text-sm text-slate-400">
                <i class="fa-regular fa-calendar-xmark mb-3 block text-3xl text-slate-300"></i>
                Không tìm thấy chuyến nào phù hợp với bộ lọc.
            </p>

            <div v-for="trip in filteredOwnerTrips" :key="trip.id"
                class="group flex max-lg:flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:border-[#1e4e57]/30 hover:shadow-md">
                <div class="relative shrink-0 overflow-hidden max-lg:!w-full max-lg:!h-52 md:max-lg:!h-48"
                    style="width: 280px; min-height: 160px;">
                    <img :src="trip.car.image" :alt="trip.car.name"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    <!-- Trip type badge -->
                    <span
                        class="absolute left-3 top-3 rounded-lg px-3 py-1.5 text-xs font-bold text-white shadow-md shadow-black/20 bg-[#1e4e57]">

                        <i :class="trip.trip_type === 0 ? 'fa-solid fa-calendar-days' : 'fa-solid fa-road'"
                            class="mr-1.5"></i>
                        {{ trip.trip_type === 0 ? 'Thuê theo ngày' : 'Thuê theo km' }}
                    </span>
                </div>

                <!-- Content -->
                <div class="flex flex-1 flex-col gap-3 p-5 max-sm:p-4 min-w-0">

                    <!-- Header row -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-slate-900 text-base max-sm:text-sm">{{ trip.car.name }}</h3>
                                <span
                                    class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">{{
                                    trip.car.license_plate }}</span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <span
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs max-sm:text-[11px] font-bold"
                            :class="statusClass(trip.status)">
                            <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(trip.status)"></span>
                            {{ statusLabel(trip.status) }}
                        </span>
                    </div>

                    <!-- Renter info -->
                    <div
                        class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-xs max-sm:text-[11px] text-slate-600">
                        <i class="fa-regular fa-user text-[#1e4e57]"></i>
                        <span class="max-sm:truncate">Người thuê: <strong class="text-slate-800">{{ trip.renter.name
                                }}</strong></span>
                        <span class="text-slate-300">·</span>
                        <span class="text-slate-500">{{ trip.renter.phone }}</span>
                    </div>

                    <!-- Time grid -->
                    <div class="flex max-sm:grid max-sm:grid-cols-2 gap-2">
                        <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                            <p class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                <i class="fa-regular fa-clock"></i>Bắt đầu
                            </p>
                            <p class="text-sm max-sm:text-xs font-semibold text-slate-700">{{ formatDate(trip.start_at)
                                }}</p>
                        </div>
                        <div class="flex-1 rounded-lg bg-slate-50 px-3 py-2">
                            <p class="mb-0.5 flex items-center gap-1 text-[11px] max-sm:text-[10px] text-slate-400">
                                <i class="fa-regular fa-flag"></i>Kết thúc
                            </p>
                            <p class="text-sm max-sm:text-xs font-semibold text-slate-700">{{ formatDate(trip.end_at) }}
                            </p>
                        </div>
                        <div
                            class="flex max-sm:col-span-2 shrink-0 items-center justify-center rounded-lg bg-slate-50 px-4 py-2">
                            <p class="text-sm max-sm:text-xs font-semibold text-slate-700 whitespace-nowrap">{{
                                duration(trip.start_at, trip.end_at) }}</p>
                        </div>
                    </div>

                    <!-- Footer row -->
                    <div class="mt-auto flex items-end justify-between">
                        <div>
                            <p class="text-xs text-slate-400">Doanh thu nhận được</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-xl font-black text-emerald-600">{{ formatCurrency(trip.cost -
                                    trip.discount_amount) }}</span>
                                <span v-if="trip.discount_amount > 0" class="text-xs text-slate-400 line-through">{{
                                    formatCurrency(trip.cost) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
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

<script setup lang="ts">
import { ref, computed } from 'vue'

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
    status: number   // 0: chưa bắt đầu | 1: đang diễn ra | 2: hoàn thành | 3: hủy bởi user | 4: hủy bởi chủ xe
    trip_type: number // 0: theo ngày | 1: theo km
    start_at: string
    end_at: string
    car_id: number
    user_id: number
    // Joined
    car: Car
    renter?: { name: string; phone: string }
}

// ─── Tabs ─────────────────────────────────────────────────────────────────────
const tabs = [
    { key: 'booked', label: 'Chuyến đã đặt', icon: 'fa-solid fa-car-side' },
    { key: 'owner', label: 'Xe cho thuê của tôi', icon: 'fa-solid fa-key' },
]
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

// ─── Demo Data ────────────────────────────────────────────────────────────────
const bookedTrips = ref<Trip[]>([
    {
        id: 1,
        cost: 1500000,
        discount_amount: 150000,
        status: 2, // hoàn thành
        trip_type: 0, // theo ngày
        start_at: '2026-05-10T08:00:00',
        end_at: '2026-05-13T08:00:00',
        car_id: 10,
        user_id: 1,
        car: {
            id: 10, name: 'Toyota Camry 2.5Q', license_plate: '51H-123.45',
            fuel_consumption: 8.5, unit_price: 500000, discount_value: 10,
            description: null, rental_terms: null,
            car_location_id: 1, car_brand_id: 1, car_type_id: 1,
            seat_count: 5, manufacture_year: '2023-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 2, delivery_option_id: 1, usage_limit_id: 1,
            image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOm7hAUSCMI5y3YBZ6k2dbbE6A_OOBCB3hZSDk4QgsD2THqK1TeJE-rKuc&s=10',
            location: 'Quận 1, TP.HCM',
        },
    },
    {
        id: 2,
        cost: 900000,
        discount_amount: 0,
        status: 1, // đang diễn ra
        trip_type: 1, // theo km
        start_at: '2026-06-12T09:00:00',
        end_at: '2026-06-14T09:00:00',
        car_id: 11,
        user_id: 1,
        car: {
            id: 11, name: 'Honda CR-V 1.5T', license_plate: '51G-456.78',
            fuel_consumption: 7.2, unit_price: 450000, discount_value: 0,
            description: null, rental_terms: null,
            car_location_id: 2, car_brand_id: 2, car_type_id: 2,
            seat_count: 7, manufacture_year: '2024-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 3, delivery_option_id: 1, usage_limit_id: 2,
            image: 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=600&q=80',
            location: 'Quận 7, TP.HCM',
        },
    },
    {
        id: 3,
        cost: 600000,
        discount_amount: 60000,
        status: 0, // chưa bắt đầu
        trip_type: 0,
        start_at: '2026-06-20T07:00:00',
        end_at: '2026-06-21T07:00:00',
        car_id: 12,
        user_id: 1,
        car: {
            id: 12, name: 'Mazda CX-5 2.5', license_plate: '51K-789.01',
            fuel_consumption: 9.0, unit_price: 600000, discount_value: 10,
            description: null, rental_terms: null,
            car_location_id: 3, car_brand_id: 3, car_type_id: 2,
            seat_count: 5, manufacture_year: '2025-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 4, delivery_option_id: 2, usage_limit_id: 1,
            image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSpBa5ryX_FgtQ0KFl3WFkuf7zSKtoh1z2vFGknXM9K9il-TrenSx25KhNo&s=10',
            location: 'Bình Thạnh, TP.HCM',
        },
    },
    {
        id: 4,
        cost: 1200000,
        discount_amount: 0,
        status: 3, // hủy bởi user
        trip_type: 0,
        start_at: '2026-04-05T08:00:00',
        end_at: '2026-04-08T08:00:00',
        car_id: 13,
        user_id: 1,
        car: {
            id: 13, name: 'Kia Seltos 1.4T', license_plate: '51F-321.65',
            fuel_consumption: 7.8, unit_price: 400000, discount_value: 0,
            description: null, rental_terms: null,
            car_location_id: 1, car_brand_id: 4, car_type_id: 2,
            seat_count: 5, manufacture_year: '2022-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 5, delivery_option_id: 1, usage_limit_id: 1,
            image: 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80',
            location: 'Gò Vấp, TP.HCM',
        },
    },
    {
        id: 5,
        cost: 2000000,
        discount_amount: 200000,
        status: 4, // hủy bởi chủ xe
        trip_type: 1,
        start_at: '2026-03-15T10:00:00',
        end_at: '2026-03-20T10:00:00',
        car_id: 14,
        user_id: 1,
        car: {
            id: 14, name: 'Hyundai Tucson 2.0', license_plate: '51B-654.32',
            fuel_consumption: 10.0, unit_price: 500000, discount_value: 10,
            description: null, rental_terms: null,
            car_location_id: 4, car_brand_id: 5, car_type_id: 2,
            seat_count: 5, manufacture_year: '2023-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 6, delivery_option_id: 2, usage_limit_id: 2,
            image: 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?w=600&q=80',
            location: 'Thủ Đức, TP.HCM',
        },
    },
])

const ownerTrips = ref<Trip[]>([
    {
        id: 101,
        cost: 1800000,
        discount_amount: 180000,
        status: 2, // hoàn thành
        trip_type: 0,
        start_at: '2026-05-01T08:00:00',
        end_at: '2026-05-04T08:00:00',
        car_id: 20,
        user_id: 10,
        car: {
            id: 20, name: 'Toyota Fortuner 2.7', license_plate: '51A-888.88',
            fuel_consumption: 12.0, unit_price: 700000, discount_value: 10,
            description: null, rental_terms: null,
            car_location_id: 1, car_brand_id: 1, car_type_id: 3,
            seat_count: 7, manufacture_year: '2024-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 1, delivery_option_id: 1, usage_limit_id: 1,
            image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0GvX1gH9YJe5FGYAPFOXCx5IYQY-6-DWACKi6qcEAUQ&s=10',
            location: 'Quận 3, TP.HCM',
        },
        renter: { name: 'Nguyễn Văn Bình', phone: '0912 345 678' },
    },
    {
        id: 102,
        cost: 700000,
        discount_amount: 0,
        status: 1, // đang diễn ra
        trip_type: 1,
        start_at: '2026-06-11T09:00:00',
        end_at: '2026-06-13T09:00:00',
        car_id: 20,
        user_id: 11,
        car: {
            id: 20, name: 'Toyota Fortuner 2.7', license_plate: '51A-888.88',
            fuel_consumption: 12.0, unit_price: 700000, discount_value: 0,
            description: null, rental_terms: null,
            car_location_id: 1, car_brand_id: 1, car_type_id: 3,
            seat_count: 7, manufacture_year: '2024-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 1, delivery_option_id: 1, usage_limit_id: 1,
            image: 'https://toyota-saigon.vn/wp-content/uploads/2023/02/corolla-cross-v-2024-8.jpeg',
            location: 'Quận 3, TP.HCM',
        },
        renter: { name: 'Trần Thị Lan', phone: '0987 654 321' },
    },
    {
        id: 103,
        cost: 1400000,
        discount_amount: 140000,
        status: 0, // chưa bắt đầu
        trip_type: 0,
        start_at: '2026-06-25T08:00:00',
        end_at: '2026-06-28T08:00:00',
        car_id: 21,
        user_id: 12,
        car: {
            id: 21, name: 'Mitsubishi Xpander', license_plate: '51C-777.77',
            fuel_consumption: 8.5, unit_price: 500000, discount_value: 10,
            description: null, rental_terms: null,
            car_location_id: 2, car_brand_id: 6, car_type_id: 4,
            seat_count: 7, manufacture_year: '2023-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 1, delivery_option_id: 2, usage_limit_id: 1,
            image: 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=600&q=80',
            location: 'Tân Bình, TP.HCM',
        },
        renter: { name: 'Lê Hoàng Nam', phone: '0903 111 222' },
    },
    {
        id: 104,
        cost: 500000,
        discount_amount: 0,
        status: 3, // hủy bởi user
        trip_type: 0,
        start_at: '2026-04-20T07:00:00',
        end_at: '2026-04-21T07:00:00',
        car_id: 21,
        user_id: 13,
        car: {
            id: 21, name: 'Mitsubishi Xpander', license_plate: '51C-777.77',
            fuel_consumption: 8.5, unit_price: 500000, discount_value: 0,
            description: null, rental_terms: null,
            car_location_id: 2, car_brand_id: 6, car_type_id: 4,
            seat_count: 7, manufacture_year: '2023-01-01',
            fuel_type: 'Xăng', transmission: 'Số tự động',
            user_id: 1, delivery_option_id: 2, usage_limit_id: 1,
            image: 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=600&q=80',
            location: 'Tân Bình, TP.HCM',
        },
        renter: { name: 'Phạm Minh Tuấn', phone: '0976 543 210' },
    },
    {
        id: 105,
        cost: 2100000,
        discount_amount: 210000,
        status: 2,
        trip_type: 1,
        start_at: '2026-03-01T10:00:00',
        end_at: '2026-03-08T10:00:00',
        car_id: 22,
        user_id: 14,
        car: {
            id: 22, name: 'Ford Ranger Wildtrak', license_plate: '51D-555.55',
            fuel_consumption: 11.5, unit_price: 650000, discount_value: 10,
            description: null, rental_terms: null,
            car_location_id: 3, car_brand_id: 7, car_type_id: 5,
            seat_count: 5, manufacture_year: '2022-01-01',
            fuel_type: 'Dầu Diesel', transmission: 'Số tự động',
            user_id: 1, delivery_option_id: 1, usage_limit_id: 2,
            image: 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=600&q=80',
            location: 'Bình Dương',
        },
        renter: { name: 'Vũ Thị Hoa', phone: '0918 222 333' },
    },
])

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

function statusLabel(status: number) {
    return ['Chưa bắt đầu', 'Đang diễn ra', 'Đã hoàn thành', 'Đã hủy (bạn)', 'Đã hủy (chủ xe)'][status] ?? '—'
}

function statusClass(status: number) {
    return [
        'bg-slate-100 text-slate-600',       // 0
        'bg-blue-50 text-blue-600',           // 1
        'bg-emerald-50 text-emerald-600',     // 2
        'bg-red-50 text-red-500',             // 3
        'bg-orange-50 text-orange-500',       // 4
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