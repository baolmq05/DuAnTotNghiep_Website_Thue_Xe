<template>
    <Transition name="fade">
        <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div 
                class="relative w-full max-w-md overflow-hidden bg-white border border-slate-100 shadow-2xl rounded-2xl transition-all duration-300 animate-scale-up"
                @click.stop
            >
                <!-- Header -->
                <div class="px-6 pt-6 pb-4 flex items-center justify-between border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <Icon name="lucide:credit-card" class="w-5 h-5 text-[#286874]" />
                        {{ title }}
                    </h3>
                    <button 
                        @click="closeModal" 
                        class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition focus:outline-none"
                    >
                        <Icon name="lucide:x" class="w-4 h-4" />
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4">
                    <p v-if="description" class="text-sm text-slate-500 text-center leading-relaxed">
                        {{ description }}
                    </p>

                    <!-- Payment summary card -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 space-y-3">
                        <div class="flex justify-between items-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <span>Loại thanh toán</span>
                            <span>Số tiền</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-semibold text-slate-700">
                            <span>{{ getPaymentTypeLabel }}</span>
                            <span class="text-base font-bold text-[#286874]">{{ formatCurrency(amount) }}</span>
                        </div>
                        <div v-if="tripId" class="flex justify-between items-center text-xs text-slate-500 pt-2.5 border-t border-dashed border-slate-200">
                            <span>Mã số chuyến đi</span>
                            <span class="font-mono font-bold bg-slate-200/50 text-slate-700 px-1.5 py-0.5 rounded">#{{ tripId }}</span>
                        </div>
                    </div>

                    <!-- Checkout guidelines -->
                    <div class="text-[11px] text-slate-500 space-y-1.5 bg-amber-50/50 border border-amber-100 rounded-lg p-3 leading-relaxed">
                        <p class="font-bold text-amber-800 flex items-center gap-1 text-xs">
                            <Icon name="lucide:info" class="w-4 h-4" />
                            Thông tin thanh toán thử nghiệm (Sandbox):
                        </p>
                        <ul class="list-disc list-inside pl-1 space-y-0.5 text-slate-600">
                            <li>Chọn ngân hàng: <strong>NCB</strong></li>
                            <li>Số thẻ: <strong>9704198526191432185</strong></li>
                            <li>Tên chủ thẻ: <strong>NGUYEN VAN A</strong></li>
                            <li>Ngày phát hành: <strong>07/15</strong></li>
                            <li>Mật khẩu OTP: <strong>123456</strong></li>
                        </ul>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                    <button 
                        @click="closeModal" 
                        class="flex-1 h-11 rounded-lg border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-100 active:scale-[0.98] transition focus:outline-none"
                    >
                        Hủy bỏ
                    </button>
                    <div class="flex-1">
                        <VNPayButton 
                            :amount="amount" 
                            :payment-type="paymentType" 
                            :trip-id="tripId"
                            label="Thanh toán" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import VNPayButton from './VNPayButton.vue'

interface Props {
    isOpen: boolean
    amount: number
    paymentType?: 'rental' | 'deposit' | 'penalty'
    tripId?: number
    title?: string
    description?: string
}

const props = withDefaults(defineProps<Props>(), {
    paymentType: 'rental',
    title: 'Xác nhận thanh toán qua VNPay',
    description: 'Hệ thống sẽ chuyển hướng bạn đến cổng thanh toán VNPay để xử lý giao dịch an toàn.'
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}

const getPaymentTypeLabel = computed(() => {
    switch (props.paymentType) {
        case 'rental':
            return 'Thanh toán thuê xe'
        case 'deposit':
            return 'Nạp tiền vào ví'
        case 'penalty':
            return 'Thanh toán tiền phạt vi phạm'
        default:
            return 'Giao dịch khác'
    }
})

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ'
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes scaleUp {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-scale-up {
    animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
