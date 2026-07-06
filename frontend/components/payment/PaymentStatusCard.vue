<template>
    <div class="w-full max-w-md mx-auto bg-white rounded-2xl border border-slate-100 shadow-xl overflow-hidden p-6 sm:p-8 text-center transition-all duration-300">
        <!-- Success State -->
        <div v-if="success" class="space-y-6">
            <!-- Animated Checkmark Icon -->
            <div class="mx-auto w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center border border-emerald-100 relative">
                <div class="absolute inset-0 rounded-full bg-emerald-100/40 animate-ping duration-1000"></div>
                <Icon name="lucide:check-circle-2" class="w-10 h-10 text-emerald-500 relative z-10" />
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-bold text-slate-800">Thanh toán thành công!</h2>
                <p class="text-sm text-slate-500">Cảm ơn bạn. Giao dịch đã được ghi nhận vào hệ thống.</p>
            </div>

            <!-- Receipt Info Card -->
            <div v-if="data" class="p-5 rounded-2xl bg-slate-50 border border-slate-100 text-left text-xs space-y-3.5">
                <div class="flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest pb-2 border-b border-slate-200/50">
                    <span>Mã hóa đơn điện tử</span>
                    <span class="font-mono text-slate-600 font-bold">#{{ data.transaction_no || 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Dịch vụ</span>
                    <span class="text-slate-800 font-bold">{{ getPaymentTypeLabel(data.payment_type) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Số tiền thanh toán</span>
                    <span class="text-base font-extrabold text-[#286874]">{{ formatCurrency(data.amount) }}</span>
                </div>
                <div v-if="getTripId" class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Mã số chuyến đi</span>
                    <span class="text-slate-800 font-bold font-mono">#{{ getTripId }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Hình thức</span>
                    <span class="text-slate-800 font-semibold flex items-center gap-1">
                        Cổng thanh toán {{ provider === 'zalopay' ? 'ZaloPay' : 'VNPay' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Thời gian giao dịch</span>
                    <span class="text-slate-800 font-semibold">{{ formatCurrentDate() }}</span>
                </div>
            </div>
        </div>

        <!-- Failure State -->
        <div v-else class="space-y-6">
            <!-- Animated Close/Error Icon -->
            <div class="mx-auto w-16 h-16 bg-red-50 rounded-full flex items-center justify-center border border-red-100 relative">
                <div class="absolute inset-0 rounded-full bg-red-100/40 animate-ping duration-1000"></div>
                <Icon name="lucide:x-circle" class="w-10 h-10 text-red-500 relative z-10" />
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-bold text-slate-800">Thanh toán thất bại</h2>
                <p class="text-sm text-slate-500 leading-relaxed">
                    {{ message || 'Giao dịch của bạn đã bị từ chối hoặc bị hủy bỏ.' }}
                </p>
            </div>

            <div class="text-[11px] text-left text-slate-500 bg-amber-50 border border-amber-100 rounded-xl p-4 leading-relaxed">
                <p class="font-bold text-amber-800 mb-1 flex items-center gap-1">
                    <Icon name="lucide:help-circle" class="w-3.5 h-3.5" /> Gợi ý khắc phục:
                </p>
                <ul class="list-disc list-inside space-y-0.5 text-slate-600">
                    <li>Kiểm tra số dư tài khoản ví / ngân hàng của bạn.</li>
                    <li v-if="provider === 'vnpay'">Sử dụng đúng thẻ test NCB VNPay Sandbox trong chế độ thử nghiệm.</li>
                    <li v-else-if="provider === 'zalopay'">Sử dụng đúng tài khoản test ZaloPay Sandbox trong chế độ thử nghiệm.</li>
                    <li>Liên hệ bộ phận chăm sóc khách hàng nếu tài khoản đã bị trừ tiền.</li>
                </ul>
            </div>
        </div>

        <!-- Redirect actions -->
        <div class="mt-8 flex flex-col gap-2.5">
            <button
                v-if="getTripId"
                @click="goToTrip"
                class="w-full h-11 rounded-lg bg-[#286874] text-white font-bold text-sm hover:bg-[#1d4f59] transition shadow-sm focus:outline-none flex items-center justify-center gap-1.5 active:scale-[0.98]"
            >
                <Icon name="lucide:car" class="w-4.5 h-4.5" />
                Xem chi tiết chuyến đi
            </button>
            <button
                @click="goToWallet"
                class="w-full h-11 rounded-lg border border-slate-205 font-bold text-sm hover:bg-slate-50 transition focus:outline-none flex items-center justify-center gap-1.5 active:scale-[0.98]"
                :class="getTripId ? 'text-slate-600' : 'bg-[#286874] text-white hover:bg-[#1d4f59] border-none shadow-sm'"
            >
                <Icon name="lucide:wallet" class="w-4 h-4" />
                Vào ví của tôi
            </button>
            <button
                @click="goToHome"
                class="w-full h-11 rounded-lg border border-slate-205 text-slate-600 font-bold text-sm hover:bg-slate-50 transition focus:outline-none flex items-center justify-center active:scale-[0.98]"
            >
                Về trang chủ
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'

interface Props {
    success: boolean
    message?: string
    data?: any
    provider?: 'vnpay' | 'zalopay'
}

const props = withDefaults(defineProps<Props>(), {
    success: false,
    message: '',
    provider: 'vnpay'
})

const router = useRouter()

const getTripId = computed(() => {
    if (!props.data) return null
    return props.data.meta?.trip_id || null
})

const getPaymentTypeLabel = (type: string) => {
    switch (type) {
        case 'rental':
            return 'Thanh toán tiền thuê xe'
        case 'deposit':
            return 'Nạp tiền vào ví điện tử'
        case 'penalty':
            return 'Thanh toán tiền phạt vi phạm'
        default:
            return 'Nạp tiền dịch vụ'
    }
}

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ'
}

const formatCurrentDate = () => {
    const d = new Date()
    return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()} ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`
}

const goToTrip = () => {
    if (getTripId.value) {
        router.push(`/trips/${getTripId.value}`)
    }
}

const goToWallet = () => {
    router.push('/mywallet')
}

const goToHome = () => {
    router.push('/')
}
</script>
