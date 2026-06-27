<template>
    <div class="w-full">
        <button
            @click="handlePay"
            :disabled="loading || disabled"
            class="w-full h-12 flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#00c49f] to-[#007aff] text-white font-bold text-sm hover:opacity-95 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#007aff]/50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm relative overflow-hidden active:scale-[0.98]"
        >
            <!-- Loading Indicator -->
            <Icon 
                v-if="loading" 
                name="lucide:loader-2" 
                class="w-5 h-5 animate-spin" 
            />
            
            <!-- ZaloPay Icon style -->
            <span v-else class="flex items-center gap-1 font-extrabold tracking-wider text-[11px] bg-white text-[#007aff] px-1.5 py-0.5 rounded uppercase shadow-sm">
                Zalo<span class="text-[#00c49f]">Pay</span>
            </span>

            <span>{{ loading ? 'Đang kết nối...' : label }}</span>
        </button>

        <!-- Dynamic Error Toast -->
        <p v-if="errorMsg" class="mt-2 text-xs text-red-500 font-medium text-center flex items-center justify-center gap-1">
            <Icon name="lucide:alert-circle" class="w-4 h-4 inline" />
            {{ errorMsg }}
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useZaloPay } from '~/composables/useZaloPay'

interface Props {
    amount: number
    paymentType?: 'rental' | 'deposit' | 'penalty'
    tripId?: number
    disabled?: boolean
    label?: string
}

const props = withDefaults(defineProps<Props>(), {
    paymentType: 'rental',
    disabled: false,
    label: 'Thanh toán qua ZaloPay'
})

const { initiatePayment, loading, error } = useZaloPay()
const errorMsg = ref<string | null>(null)

const handlePay = async () => {
    errorMsg.value = null
    try {
        await initiatePayment(props.amount, props.paymentType, props.tripId)
    } catch (err: any) {
        errorMsg.value = error.value || err.message || 'Thanh toán thất bại.'
    }
}
</script>
