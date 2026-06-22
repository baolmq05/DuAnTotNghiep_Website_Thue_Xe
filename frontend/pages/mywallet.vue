<template>
    <div class="min-h-screen bg-[#f8f9fa] font-sans text-[#333333] pb-24 antialiased">

        <div class="bg-[#286874] pt-24 pb-12 text-center">
            <h2 class="text-[32px] font-bold text-white tracking-tight">
                Ví của tôi
            </h2>
        </div>

        <div class="max-w-4xl mx-auto px-4 -mt-6 relative z-10">
            <div class="bg-white rounded-xl border border-slate-200 p-6 relative shadow-sm text-center">
                <button
                    @click="goBack"
                    class="absolute left-6 top-6 flex items-center gap-1 text-slate-500 hover:text-black transition text-sm font-medium focus:outline-none">
                    <Icon name="lucide:chevron-left" class="w-4 h-4" />
                    Quay lại
                </button>
                <div class="py-2">
                    <p class="text-slate-500 text-[13px] font-medium">
                        Số dư ví
                    </p>
                    <p class="text-4xl font-bold text-[#286874] mt-1.5 tracking-tight">
                        {{ formatCurrency(balance) }}
                    </p>
                </div>

            </div>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mt-6 shadow-sm">
                <div class="bg-[#111111] text-white px-5 py-3.5 flex items-center justify-between">
                    <h3 class="font-bold text-xs tracking-wider uppercase">
                        Bảng tổng hợp giao dịch
                    </h3>

                    <div class="flex items-center gap-1 text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <span>Tháng 06-2026</span>
                        <Icon name="lucide:chevron-down" class="w-3 h-3 text-slate-400" />
                    </div>
                </div>

                <div class="flex w-full text-center bg-white border-b border-slate-200 divide-x divide-slate-100">
                    <div class="flex-1 py-3.5 flex flex-col justify-center items-center">
                        <p
                            class="text-base font-bold text-slate-800 flex items-center justify-center gap-0.5 leading-none">
                            <span class="text-amber-400 text-sm">★</span> {{ rating.toFixed(1) }}
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Đánh giá</p>
                    </div>

                    <div class="flex-1 py-3.5 flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ completedTripsCount }}</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Chuyến đi thành công</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-white flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ responseRate }}%</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Tỉ lệ phản hồi</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-white flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ responseTime }}</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Phản hồi trong</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-white flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ acceptRate }}%</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Tỉ lệ đồng ý</p>
                    </div>
                </div>

                <div class="bg-white text-xs font-medium text-slate-700 divide-y divide-slate-100">
                    <!-- <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Chuyến đi hoàn thành</span>
                        <span class="font-semibold text-slate-800">{{ formatCurrency(summary.completed_trips_change) }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Giao dịch rút/nộp tiền</span>
                        <span class="font-semibold text-slate-800">{{ formatCurrency(summary.deposit_withdrawal_change) }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Giao dịch hủy chuyến</span>
                        <span class="font-semibold text-slate-800">{{ formatCurrency(summary.cancelled_trips_change) }}</span>
                    </div> -->

                    <div class="p-5 space-y-3 bg-white">
                        <div class="flex justify-between font-bold text-slate-400 text-[11px] tracking-wider">
                            <span>TỔNG CỘNG THAY ĐỔI TRONG KÌ</span>
                            <span class="text-slate-800">{{ formatCurrency(summary.total_change) }}</span>
                        </div>
                        <div class="flex justify-between font-normal text-slate-700">
                            <span>TIỀN ĐẦU KÌ</span>
                            <span class="text-slate-800">0đ</span>
                        </div>
                        <div class="flex justify-between font-bold text-[#286874]">
                            <span>TIỀN CUỐI KÌ</span>
                            <span>{{ formatCurrency(summary.end_balance) }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-[#e05638]">
                            <span>THUẾ KINH DOANH ĐÃ KHẤU TRỪ</span>
                            <span>({{ formatCurrency(summary.tax_deducted) }})</span>
                        </div>
                        <div class="flex justify-between font-bold text-[#2f80ed]">
                            <span>THU NHẬP CHỦ XE</span>
                            <span>{{ formatCurrency(summary.owner_income) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 mt-6">
                <!-- <button
                    class="h-12 rounded-lg bg-[#286874] text-white font-bold text-sm hover:bg-[#1d4f59] transition-colors focus:outline-none shadow-sm">
                    Gửi yêu cầu rút tiền
                </button> -->

                <button
                    @click="navigateToStatement"
                    class="h-12 rounded-lg border border-[#286874] text-[#286874] font-bold text-sm hover:bg-[#286874]/5 transition-colors text-center focus:outline-none shadow-sm">
                    Xem Sao kê chi tiết giao dịch
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { walletService } from '~/services/wallet.service'
import { useRouter } from 'vue-router'

const router = useRouter()

const balance = ref(0)
const rating = ref(5.0)
const completedTripsCount = ref(0)
const responseRate = ref(100)
const responseTime = ref('5 phút')
const acceptRate = ref(100)

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

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ'
}

const loadWalletDetails = async () => {
    try {
        const response = await walletService.getWalletDetails()
        if (response.success && response.data) {
            balance.value = response.data.balance
            rating.value = response.data.rating
            completedTripsCount.value = response.data.completed_trips_count
            responseRate.value = response.data.response_rate
            responseTime.value = response.data.response_time
            acceptRate.value = response.data.accept_rate
            summary.value = response.data.summary
        }
    } catch (error) {
        console.error('Error loading wallet details:', error)
    }
}

onMounted(() => {
    loadWalletDetails()
})

const navigateToStatement = () => {
    router.push('/mymonthlyreport')
}

const goBack = () => {
    router.back()
}
</script>

<style scoped>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}
</style>