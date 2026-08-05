<template>
    <div class="min-h-screen bg-slate-50 font-sans text-[#333333] pb-12 antialiased">
        <CommonLoadingOverlay :loading="isLoading" text="Đang tải dữ liệu..." />

        <section class="bg-gradient-to-r from-[#1e4e57] to-[#286874] text-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 md:pt-28 md:pb-20 text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight">
                    Sao kê chi tiết giao dịch
                </h1>

                <div class="mt-5 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-xl shadow-inner">
                        <Icon name="lucide:calendar" class="w-4 h-4 text-cyan-200" />
                        <span class="text-xs font-semibold text-cyan-100 uppercase tracking-wider">Kỳ sao kê:</span>
                        <select
                            v-model="selectedMonthYear"
                            @change="onMonthChange"
                            class="bg-transparent text-white font-bold text-sm focus:outline-none cursor-pointer pr-1">
                            <option
                                v-for="opt in monthOptions"
                                :key="opt.value"
                                :value="opt.value"
                                class="text-slate-800 bg-white">
                                {{ opt.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-6 mt-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-start w-full">
                    <button
                        @click="goBack"
                        class="flex items-center gap-1 text-slate-500 hover:text-black transition text-sm font-medium focus:outline-none pt-2">
                        <Icon name="lucide:chevron-left" class="w-4 h-4" />
                        Quay lại
                    </button>

                    <!-- KHỐI THÔNG TIN CHỦ XE: Bị đẩy sát về góc bên phải -->
                    <div
                        class="rounded-2xl shadow-lg shadow-[#286874]/5 bg-white p-4 md:p-5 w-full max-w-sm border border-slate-100">
                        <table class="w-full border-separate border-spacing-y-1 text-sm">
                            <tbody>
                                <tr>
                                    <td class="w-20 text-slate-500 font-bold">
                                        {{ user?.role_id === 2 ? 'Khách hàng' : 'Chủ xe' }}
                                    </td>
                                    <td class="bg-slate-50 px-3 py-1.5 text-slate-800 font-semibold rounded-lg">
                                        {{ userName }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500 font-bold">Mã số</td>
                                    <td
                                        class="bg-slate-50 px-3 py-1.5 text-slate-800 font-mono font-semibold rounded-lg">
                                        {{ userCode }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-6 space-y-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

                <!-- 1. CHUYẾN ĐI HOÀN THÀNH -->
                <div class="space-y-3">
                    <h2 class="text-base font-bold text-slate-900 px-1">Chuyến đi hoàn thành trong tháng</h2>
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-center text-[11px] border-collapse min-w-[1150px]">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                                        <th colspan="2" class="py-2.5 border-r border-slate-200"></th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thời gian</th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thông tin chuyến đi</th>
                                        <th colspan="2" class="py-2.5 border-r border-slate-200">Thanh toán</th>
                                        <th colspan="4" class="py-2.5"></th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                                        <!-- <th class="py-3 px-1 border-r border-slate-200">Mã chuyến đi</th> -->
                                        <th class="py-3 px-1 border-r border-slate-200">Biển số xe</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Ngày đi</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Ngày về</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Ngày đặt xe</th>
                                        <th class="py-3 px-1 border-r border-gray-200">
                                            {{ user?.role_id === 2 ? 'Chủ xe' : 'Khách hàng' }}
                                        </th>
                                        <th class="py-3 px-1 border-r border-gray-200">Xe thuê</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Đơn giá</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Thanh toán giữ chỗ</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Thanh toán chủ xe</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Giảm giá KM</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Giữ phạt nguội (2%)</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Thuế khấu trừ (25%)</th>
                                        <th class="py-3 px-1">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="completedTrips.length > 0">
                                        <tr v-for="item in completedTrips" :key="item.id" class="border-t border-slate-100 hover:bg-slate-50/50 bg-white">
                                            <!-- <td class="py-3 px-1 border-r border-slate-100 font-mono font-bold text-slate-800">
                                                TRIP{{ item.trip.id }}
                                            </td> -->
                                            <td class="py-3 px-1 border-r border-slate-100 font-semibold">
                                                {{ item.trip.car?.license_plate || 'N/A' }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-slate-500 font-mono">
                                                {{ item.trip.start_at }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-slate-500 font-mono">
                                                {{ item.trip.end_at }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-slate-500 font-mono">
                                                {{ item.trip.created_at }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-medium">
                                                {{ user?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-left pl-3 text-slate-700 font-medium">
                                                {{ item.trip.car?.name || 'N/A' }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono">
                                                {{ formatCurrency(item.trip.car?.unit_price || 0) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-emerald-600">
                                                {{ formatCurrency(item.prepay) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-blue-600">
                                                {{ formatCurrency(item.trip.cost) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-rose-500">
                                                {{ formatCurrency(item.trip.discount_amount) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-amber-600">
                                                {{ formatCurrency(item.trip.penalty_deducted || (item.amount * 0.02)) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-orange-500">
                                                {{ formatCurrency(item.trip.tax_deducted || (item.amount * 0.25)) }}
                                            </td>
                                            <td class="py-3 px-1 font-mono font-bold text-emerald-600">
                                                +{{ formatCurrency(item.amount) }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="14" class="py-8 text-slate-400 text-center font-medium bg-white">
                                            Không có chuyến đi hoàn thành trong kỳ.
                                        </td>
                                    </tr>
                                    <tr class="border-t border-slate-200 bg-slate-50/50">
                                        <td colspan="13"
                                            class="text-right py-3.5 font-bold text-slate-900 pr-12 text-xs">Tổng thay đổi - Chuyến đi hoàn thành</td>
                                        <td class="py-3.5 font-bold text-slate-900 text-xs text-center">
                                            {{ formatCurrency(summary.completed_trips_change) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. RÚT NỘP TIỀN -->
                <div class="space-y-3 max-w-xl">
                    <h2 class="text-base font-bold text-slate-900 px-1">Giao dịch rút/nộp tiền trong tháng</h2>
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <table class="w-full text-center text-[11px] border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                                    <th class="py-3 border-r border-slate-200 w-1/3">Ngày giao dịch</th>
                                    <th class="py-3 border-r border-slate-200 w-1/3">Nội dung</th>
                                    <th class="py-3 w-1/3">Thay đổi số dư</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="depositWithdrawals.length > 0">
                                    <tr v-for="item in depositWithdrawals" :key="item.id" class="border-t border-slate-100 hover:bg-slate-50/50 bg-white">
                                        <td class="py-3.5 border-r border-slate-100 font-mono text-slate-500">{{ item.created_at }}</td>
                                        <td class="py-3.5 border-r border-slate-100 font-medium text-slate-800 text-left pl-6">
                                            {{ item.description || ('Giao dịch ' + (item.amount > 0 ? 'Nạp tiền' : 'Rút tiền')) }} 
                                        </td>
                                        <td class="py-3.5 font-mono font-bold" :class="item.amount > 0 ? 'text-emerald-600' : 'text-rose-600'">
                                            {{ item.amount > 0 ? '+' : '' }}{{ formatCurrency(item.amount) }}
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="3" class="py-6 text-slate-400 text-center font-medium bg-white">
                                        Không có giao dịch rút/nộp tiền trong kỳ.
                                    </td>
                                </tr>
                                <!-- <tr class="border-t border-slate-200 bg-slate-50/50">
                                    <td colspan="2" class="text-left pl-6 py-3.5 font-bold text-slate-900 text-xs">Tổng thay đổi - Giao dịch rút/nộp tiền</td>
                                    <td class="py-3.5 font-bold text-slate-900 text-xs text-center">
                                        {{ formatCurrency(summary.deposit_withdrawal_change) }}
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. HỦY CHUYẾN -->
                <div class="space-y-3">
                    <h2 class="text-base font-bold text-slate-900 px-1">Giao dịch hủy chuyến trong tháng</h2>
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-center text-[11px] border-collapse min-w-[1100px]">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                                        <th colspan="2" class="py-2.5 border-r border-slate-200"></th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thời gian</th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thông tin chuyến đi</th>
                                        <th colspan="2" class="py-2.5 border-r border-slate-200">Thanh toán</th>
                                        <th colspan="2" class="py-2.5"></th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                                        <!-- <th class="py-3 px-1 border-r border-slate-200">Mã chuyến đi</th> -->
                                        <th class="py-3 px-1 border-r border-slate-200">Biển số xe</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Ngày đi</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Ngày về</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Ngày hủy chuyến</th>
                                        <th class="py-3 px-1 border-r border-slate-200">
                                            {{ user?.role_id === 2 ? 'Chủ xe' : 'Khách hàng' }}
                                        </th>
                                        <th class="py-3 px-1 border-r border-slate-200">Xe thuê</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Đơn giá</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Thanh toán giữ chỗ</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Thanh toán chủ xe</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Nội dung hủy chuyến</th>
                                        <th class="py-3 px-1">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="cancelledTrips.length > 0">
                                        <tr v-for="item in cancelledTrips" :key="item.id" class="border-t border-slate-100 hover:bg-slate-50/50 bg-white">
                                            <!-- <td class="py-3 px-1 border-r border-slate-100 font-mono font-bold text-slate-800">
                                                TRIP{{ item.trip.id }}
                                            </td> -->
                                            <td class="py-3 px-1 border-r border-slate-100 font-semibold">
                                                {{ item.trip.car?.license_plate || 'N/A' }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-slate-500 font-mono">
                                                {{ item.trip.start_at }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-slate-500 font-mono">
                                                {{ item.trip.end_at }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-slate-500 font-mono">
                                                {{ item.trip?.updated_at || item.created_at }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-medium">
                                                {{ user?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 text-left pl-3 text-slate-700 font-medium">
                                                {{ item.trip.car?.name || 'N/A' }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono">
                                                {{ formatCurrency(item.trip.car?.unit_price || 0) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-slate-400">
                                                {{ formatCurrency(item.prepay) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-slate-400">
                                                {{ formatCurrency(item.trip.cost) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-medium text-rose-500 text-left pl-2">
                                                {{ item.trip?.cancel_by_name || (Number(item.trip?.status) === TripStatus.UserCancel ? 'Người thuê hủy' : 'Chủ xe hủy') }}
                                            </td>
                                            <td class="py-3 px-1 font-mono font-bold text-rose-600">
                                                {{ formatCurrency(item.amount) }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="12" class="py-8 text-slate-400 text-center font-medium bg-white">
                                            Không có giao dịch hủy chuyến trong kỳ.
                                        </td>
                                    </tr>
                                    <tr class="border-t border-slate-200 bg-slate-50/50">
                                        <td colspan="11"
                                            class="text-right py-3.5 font-bold text-slate-900 pr-12 text-xs">Tổng thay đổi - Giao dịch hủy chuyến</td>
                                        <td class="py-3.5 font-bold text-slate-900 text-xs text-center">
                                            {{ formatCurrency(summary.cancelled_trips_change) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- BẢNG TỔNG HỢP TIỀN -->
                <div
                    class="rounded-2xl border border-slate-200/60 bg-white text-xs font-medium text-slate-700 divide-y divide-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 space-y-3 bg-white">
                        <template v-if="user?.role_id !== 2">
                            <div class="flex justify-between font-bold text-slate-800">
                                <span>TỔNG TIỀN CHUYẾN ĐI TRONG THÁNG</span>
                                <span class="text-[#286874]">{{ formatCurrency(summary.completed_trips_change) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-[#e05638]">
                                <span>THUẾ KINH DOANH ĐÃ KHẤU TRỪ ({{ summary.tax_rate || 25 }}%)</span>
                                <span>({{ formatCurrency(summary.tax_deducted) }})</span>
                            </div>
                            <div class="flex justify-between font-semibold text-amber-600">
                                <span>TIỀN GIỮ PHẠT NGUỘI ({{ summary.penalty_rate || 2 }}%)</span>
                                <span>({{ formatCurrency(summary.penalty_deducted || 0) }})</span>
                            </div>
                            <div class="flex justify-between font-bold text-[#2f80ed] pt-2 border-t border-slate-100">
                                <span>THU NHẬP CHỦ XE</span>
                                <span>{{ formatCurrency(summary.owner_income) }}</span>
                            </div>
                        </template>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 italic leading-relaxed text-left px-1">
                    Ghi chú: Mọi vấn đề thắc mắc về thông tin ghi nhận trên bản sao kê chi tiết giao dịch, Quý đối tác
                    vui lòng liên hệ với bộ phận Chăm Sóc Khách Hàng của Drivio tại 1900 9217 để biết thêm chi tiết. Xin
                    cám ơn!
                </p>

            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { walletService } from '~/services/wallet.service'
import { authService } from '~/services/auth.service'
import { useRouter } from 'vue-router'
import { TripStatus } from '~/config/trip-status'

// 1. Khai báo các biến lưu trữ dữ liệu (reactive state)
const { user } = useAuth()
const router = useRouter()

const transactions = ref<any[]>([])
const refunds = ref<any[]>([])
const userName = ref('')
const userCode = ref('')
const balance = ref(0)
const isLoading = ref(true)

const summary = ref({
    completed_trips_change: 0,
    deposit_withdrawal_change: 0,
    cancelled_trips_change: 0,
    total_change: 0,
    start_balance: 0,
    end_balance: 0,
    tax_rate: 25,
    penalty_rate: 2,
    tax_deducted: 0,
    penalty_deducted: 0,
    owner_income: 0
})

const now = new Date()
const selectedMonthYear = ref(`${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`)

const monthOptions = computed(() => {
    const options = []
    const currentDate = new Date()
    for (let i = 0; i < 12; i++) {
        const d = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1)
        const m = String(d.getMonth() + 1).padStart(2, '0')
        const y = d.getFullYear()
        options.push({
            label: `Tháng ${m}/${y}`,
            value: `${y}-${m}`,
            month: d.getMonth() + 1,
            year: y
        })
    }
    return options
})

const selectedMonth = computed(() => {
    const parts = selectedMonthYear.value.split('-')
    return parseInt(parts[1] || '0', 10)
})

const selectedYear = computed(() => {
    const parts = selectedMonthYear.value.split('-')
    return parseInt(parts[0] || '0', 10)
})

// 2. Tự động tính Từ ngày (ngày 01) và Đến ngày (ngày cuối cùng) của tháng được chọn
const fromDate = computed(() => {
    const day = '01'
    const month = String(selectedMonth.value).padStart(2, '0')
    const year = selectedYear.value
    return `${day}/${month}/${year}`
})

const toDate = computed(() => {
    const lastDayObj = new Date(selectedYear.value, selectedMonth.value, 0)
    const day = String(lastDayObj.getDate()).padStart(2, '0')
    const month = String(selectedMonth.value).padStart(2, '0')
    const year = selectedYear.value
    return `${day}/${month}/${year}`
})

const isSameMonthYear = (dateStr?: string | null): boolean => {
    if (!dateStr || typeof dateStr !== 'string') return false
    const trimmed = dateStr.trim()
    if (!trimmed) return false

    const parts = trimmed.split(' ')
    const cleanDate = parts[0] || ''
    if (!cleanDate) return false

    let m = 0, y = 0
    if (cleanDate.includes('/')) {
        const dateParts = cleanDate.split('/')
        if (dateParts.length === 3) {
            m = parseInt(dateParts[1] || '0', 10)
            y = parseInt(dateParts[2] || '0', 10)
        }
    } else if (cleanDate.includes('-')) {
        const dateParts = cleanDate.split('-')
        if (dateParts.length === 3) {
            y = parseInt(dateParts[0] || '0', 10)
            m = parseInt(dateParts[1] || '0', 10)
        }
    }
    return m === selectedMonth.value && y === selectedYear.value
}

// 3. Phân loại danh sách giao dịch theo Tháng/Năm được chọn (Computed Properties)
const completedTrips = computed(() => {
    return transactions.value.filter(t => {
        if (!t.trip || Number(t.trip.status) !== TripStatus.Complete) return false
        const dateToCheck = t.trip.end_at || t.trip.updated_at || t.trip.start_at || t.created_at
        return isSameMonthYear(dateToCheck)
    })
})

const depositWithdrawals = computed(() => {
    if (refunds.value && refunds.value.length > 0) {
        return refunds.value.filter(r => isSameMonthYear(r.created_at))
    }
    return transactions.value.filter(t => !t.trip && isSameMonthYear(t.created_at))
})

const cancelledTrips = computed(() => {
    return transactions.value.filter(t => {
        if (!t.trip) return false
        const isCancelled = Number(t.trip.status) === TripStatus.UserCancel || Number(t.trip.status) === TripStatus.OwnerCancel
        if (!isCancelled) return false
        const dateToCheck = t.trip.end_at || t.trip.updated_at || t.trip.start_at || t.created_at
        return isSameMonthYear(dateToCheck)
    })
})

// 3. Hàm trợ giúp định dạng tiền tệ VND
const formatCurrency = (amount: number = 0) => {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ'
}

// 4. Hàm bất đồng bộ (async) gọi API lấy dữ liệu từ Service theo Tháng/Năm được chọn
const fetchReportData = async () => {
    isLoading.value = true
    try {
        if (user.value) {
            userName.value = user.value.name || 'Khách hàng'
            userCode.value = user.value.national_number || ('DRV' + String(user.value.id || '001').padStart(3, '0'))
        }

        // Gọi API lấy thông tin Ví & Sao kê giao dịch theo month & year
        const walletRes = await walletService.getWalletDetails({
            month: selectedMonth.value,
            year: selectedYear.value
        })

        if (walletRes && walletRes.success && walletRes.data) {
            const rawTxns = walletRes.data.transactions
            transactions.value = Array.isArray(rawTxns) ? rawTxns : Object.values(rawTxns || {})

            const rawRefunds = walletRes.data.refunds
            refunds.value = Array.isArray(rawRefunds) ? rawRefunds : Object.values(rawRefunds || {})

            balance.value = walletRes.data.balance || 0
            if (walletRes.data.summary) {
                summary.value = { ...summary.value, ...walletRes.data.summary }
            }
        }
    } catch (error) {
        console.error('Lỗi khi tải dữ liệu sao kê báo cáo tháng:', error)
    } finally {
        isLoading.value = false
    }
}

const onMonthChange = () => {
    fetchReportData()
}

// 5. Tự động gọi API khi component được Mounted vào DOM
onMounted(() => {
    fetchReportData()
})

const goBack = () => {
    router.back()
}
</script>

<style scoped>
table {
    border-spacing: 0;
}

th,
td {
    word-break: break-all;
}
</style>