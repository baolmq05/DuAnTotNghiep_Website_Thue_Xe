import { ref } from 'vue'
import { API_URL } from '~/enviroment/enviroment'

export function useVNPay() {
    const loading = ref(false)
    const error = ref<string | null>(null)

    const getToken = (): string | null => {
        const tokenCookie = useCookie<string | null>("USER_TOKEN").value
        if (tokenCookie) {
            return tokenCookie
        }
        if (typeof window !== "undefined" && localStorage.getItem("USER_TOKEN")) {
            return localStorage.getItem("USER_TOKEN")
        }
        return null
    }

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
            const token = getToken()
            if (!token) {
                throw new Error("Bạn cần đăng nhập để thực hiện thanh toán.")
            }

            const response = await $fetch<{ success: boolean; payment_url?: string; message?: string }>(
                `${API_URL}auth/vnpay/create-payment`, 
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: {
                        payment_type: paymentType,
                        amount: amount,
                        trip_id: tripId
                    }
                }
            )

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
            const queryString = new URLSearchParams(queryParams as any).toString()
            const response = await $fetch<{ success: boolean; message: string; data?: any }>(
                `${API_URL}vnpay/verify?${queryString}`
            )
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
