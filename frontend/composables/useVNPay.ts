import { ref } from 'vue'
import { paymentService } from '~/services/payment.service'

export function useVNPay() {
    const loading = ref(false)
    const error = ref<string | null>(null)

    /**
     * Initiate payment redirect to VNPay
     */
    const initiatePayment = async (
        amount: number, 
        paymentType: 'rental' | 'deposit' | 'penalty', 
        tripId?: number
    ): Promise<void> => {
        loading.value = true
        error.value = null
        try {
            const response = await paymentService.createVNPayPayment(amount, paymentType, tripId)

            if (response.success && response.payment_url) {
                // Redirect user to VNPay Sandbox checkout
                window.location.href = response.payment_url
            } else {
                throw new Error(response.message || "Tạo liên kết thanh toán thất bại.")
            }
        } catch (err: any) {
            console.error('VNPay initiation failed:', err)
            error.value = err.data?.message || err.message || "Đã xảy ra lỗi khi tạo liên kết thanh toán."
            throw err
        } finally {
            loading.value = false
        }
    }

    /**
     * Verify payment status using returning query parameters from VNPay redirect
     */
    const verifyPayment = async (
        queryParams: Record<string, any>
    ): Promise<{ success: boolean; message: string; data?: any }> => {
        loading.value = true
        error.value = null
        try {
            const response = await paymentService.verifyVNPayPayment(queryParams)
            return response
        } catch (err: any) {
            console.error('VNPay verification failed:', err)
            const errMsg = err.data?.message || err.message || "Xác thực giao dịch thất bại."
            error.value = errMsg
            return {
                success: false,
                message: errMsg
            }
        } finally {
            loading.value = false
        }
    }

    return {
        loading,
        error,
        initiatePayment,
        verifyPayment
    }
}
