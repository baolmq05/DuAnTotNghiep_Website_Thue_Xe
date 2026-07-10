<template>
    <div class="min-h-screen bg-[#f8f9fa] flex items-center justify-center p-4 font-sans text-[#333333] antialiased">
        <div class="w-full max-w-md">
            <!-- Loading verification state -->
            <div v-if="verifying" class="text-center py-12 space-y-4 bg-white rounded-2xl border border-slate-100 shadow-md p-8">
                <Icon name="lucide:loader-2" class="w-12 h-12 text-[#286874] animate-spin mx-auto" />
                <h3 class="text-base font-bold text-slate-700">Đang xác thực giao dịch</h3>
                <p class="text-xs text-slate-400">Vui lòng không tắt hoặc tải lại trang này...</p>
            </div>

            <!-- Verified transaction status card -->
            <PaymentStatusCard
                v-else
                :success="isSuccess"
                :message="resultMessage"
                :data="paymentData"
                :provider="provider"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useVNPay } from '~/composables/useVNPay'
import { useZaloPay } from '~/composables/useZaloPay'
import PaymentStatusCard from '~/components/payment/PaymentStatusCard.vue'

definePageMeta({
    layout: 'profile-no-sidebar',
})

const route = useRoute()
const { verifyPayment: verifyVNPay } = useVNPay()
const { verifyPayment: verifyZaloPay } = useZaloPay()

const verifying = ref(true)
const isSuccess = ref(false)
const resultMessage = ref('')
const paymentData = ref<any>(null)
const provider = ref<'vnpay' | 'zalopay'>('vnpay')

onMounted(async () => {
    const queryParams = route.query

    if (Object.keys(queryParams).length === 0) {
        verifying.value = false
        isSuccess.value = false
        resultMessage.value = 'Không tìm thấy thông tin giao dịch thanh toán.'
        return
    }

    try {
        let response;
        if (queryParams.vnp_TxnRef) {
            provider.value = 'vnpay'
            response = await verifyVNPay(queryParams)
        } else if (queryParams.apptransid || queryParams.app_trans_id) {
            provider.value = 'zalopay'
            const appTransId = (queryParams.apptransid || queryParams.app_trans_id) as string
            response = await verifyZaloPay(appTransId)
        } else {
            verifying.value = false
            isSuccess.value = false
            resultMessage.value = 'Cổng thanh toán không được hỗ trợ.'
            return
        }
        
        isSuccess.value = response.success
        resultMessage.value = response.message
        if (response.success && response.data) {
            paymentData.value = response.data
        }
    } catch (err: any) {
        isSuccess.value = false
        resultMessage.value = 'Đã xảy ra lỗi bất ngờ khi xác thực giao dịch của bạn.'
    } finally {
        verifying.value = false
    }
})
</script>

<style scoped>
body {
    background-color: #f8f9fa;
}
</style>
