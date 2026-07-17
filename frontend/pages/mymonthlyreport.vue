<template>
    <div class="min-h-screen bg-slate-50 font-sans text-[#333333] pb-12 antialiased">
        <section class="bg-gradient-to-r from-[#1e4e57] to-[#286874] text-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 md:pt-28 md:pb-20 text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight">
                    Sao kê chi tiết giao dịch
                </h1>

                <div class="flex items-center justify-center gap-2 text-sm text-cyan-50/90 mt-4 font-medium">
                    <span>Từ ngày</span>
                    <span
                        class="bg-white/10 border border-white/20 px-3 py-1 rounded font-mono text-white">01/06/2026</span>
                    <span class="ml-2">Đến ngày</span>
                    <span
                        class="bg-white/10 border border-white/20 px-3 py-1 rounded font-mono text-white">30/06/2026</span>
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
                    <h2 class="text-base font-bold text-slate-900 px-1">Chuyến đi hoàn thành trong kì</h2>
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
                                        <th class="py-3 px-1 border-r border-slate-200">Mã chuyến đi</th>
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
                                        <th class="py-3 px-1 border-r border-gray-200">Phí dịch vụ</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Thuế khấu trừ</th>
                                        <th class="py-3 px-1">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="completedTrips.length > 0">
                                        <tr v-for="item in completedTrips" :key="item.id" class="border-t border-slate-100 hover:bg-slate-50/50 bg-white">
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono font-bold text-slate-800">
                                                TRIP{{ item.trip.id }}
                                            </td>
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
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-slate-400">
                                                {{ formatCurrency(item.trip.service_fee || 0) }}
                                            </td>
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono text-orange-500">
                                                {{ formatCurrency(item.trip.tax_deducted || 0) }}
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
                    <h2 class="text-base font-bold text-slate-900 px-1">Giao dịch rút/nộp tiền trong kì</h2>
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
                                            Giao dịch {{ item.amount > 0 ? 'Nạp tiền' : 'Rút tiền' }} (Mã GD: {{ item.transaction_code }})
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
                                <tr class="border-t border-slate-200 bg-slate-50/50">
                                    <td colspan="2" class="text-left pl-6 py-3.5 font-bold text-slate-900 text-xs">Tổng thay đổi - Giao dịch rút/nộp tiền</td>
                                    <td class="py-3.5 font-bold text-slate-900 text-xs text-center">
                                        {{ formatCurrency(summary.deposit_withdrawal_change) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. HỦY CHUYẾN -->
                <div class="space-y-3">
                    <h2 class="text-base font-bold text-slate-900 px-1">Giao dịch hủy chuyến trong kì</h2>
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
                                        <th class="py-3 px-1 border-r border-slate-200">Mã chuyến đi</th>
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
                                            <td class="py-3 px-1 border-r border-slate-100 font-mono font-bold text-slate-800">
                                                TRIP{{ item.trip.id }}
                                            </td>
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
                                                {{ item.created_at }}
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
                                                Hủy chuyến (Khấu trừ hoàn cọc)
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
                        <div class="flex justify-between font-bold text-slate-400 text-[11px] tracking-wider uppercase">
                            <span>TỔNG CỘNG THAY ĐỔI TRONG KÌ</span>
                            <span class="text-slate-800">{{ formatCurrency(summary.total_change) }}</span>
                        </div>
                        <div class="flex justify-between font-normal text-slate-700">
                            <span>TIỀN ĐẦU KÌ</span>
                            <span class="text-slate-800">{{ formatCurrency(summary.start_balance) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-[#286874]">
                            <span>TIỀN CUỐI KÌ</span>
                            <span>{{ formatCurrency(summary.end_balance) }}</span>
                        </div>
                        <div v-if="user?.role_id !== 2" class="flex justify-between font-semibold text-[#e05638]">
                            <span>THUẾ KINH DOANH ĐÃ KHẤU TRỪ</span>
                            <span>({{ formatCurrency(summary.tax_deducted) }})</span>
                        </div>
                        <div v-if="user?.role_id !== 2" class="flex justify-between font-bold text-[#2f80ed]">
                            <span>THU NHẬP CHỦ XE</span>
                            <span>{{ formatCurrency(summary.owner_income) }}</span>
                        </div>
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

const { user } = useAuth()

const router = useRouter()

const transactions = ref<any[]>([])
const userName = ref('')
const userCode = ref('')
const balance = ref(0)

const summary = ref({
    completed_trips_change: 0,
    deposit_withdrawal_change: 0,
    cancelled_trips_change: 0,
    total_change: 0,
    start_balance: 0,
    end_balance: 0,
    tax_deducted: 0,
    owner_income: 0
})

const completedTrips = computed(() => {
    return transactions.value.filter(t => t.trip && t.trip.status !== TripStatus.UserCancel && t.trip.status !== TripStatus.OwnerCancel)
})

const depositWithdrawals = computed(() => {
    return transactions.value.filter(t => !t.trip)
})

const cancelledTrips = computed(() => {
    return transactions.value.filter(t => t.trip && (t.trip.status === TripStatus.UserCancel || t.trip.status === TripStatus.OwnerCancel))
})

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ'
}

const intval = (value: number) => {
    return Math.floor(value)
}

const loadData = async () => {
    try {
        const walletRes = await walletService.getWalletDetails()
        if (walletRes.success && walletRes.data) {
            transactions.value = walletRes.data.transactions
            balance.value = walletRes.data.balance
            summary.value = walletRes.data.summary
        }

        const profileRes = await authService.getProfileApi()
        if (profileRes) {
            userName.value = profileRes.name || 'Khách hàng'
            userCode.value = profileRes.national_number || 'DRV' + (profileRes.id || '001')
        }
    } catch (error) {
        console.error('Error loading report details:', error)
    }
}

onMounted(() => {
    loadData()
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