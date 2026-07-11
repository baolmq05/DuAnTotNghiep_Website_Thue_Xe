import { ref } from "vue";
import { paymentService } from "~/services/payment.service";

export function useZaloPay() {
    const loading = ref(false);
    const error = ref<string | null>(null);

    /**
     * Tạo giao dịch ZaloPay
     */
    const initiatePayment = async (
        amount: number,
        paymentType: "rental" | "deposit" | "penalty" | "extension",
        tripId?: number
    ): Promise<void> => {
        loading.value = true;
        error.value = null;

        try {
            const response = await paymentService.createZaloPayPayment(
                amount,
                paymentType,
                tripId
            );

            if (response.success && response.payment_url) {
                window.location.href = response.payment_url;
            } else {
                throw new Error(
                    response.message ?? "Không thể tạo đơn thanh toán."
                );
            }
        } catch (err: any) {
            console.error(err);
            error.value =
                err.data?.message ?? err.message ?? "Lỗi ZaloPay.";
            throw err;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Verify giao dịch
     */
    const verifyPayment = async (appTransId: string) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await paymentService.verifyZaloPayPayment(appTransId);
            return response;
        } catch (err: any) {
            error.value = err.data?.message ?? err.message;
            return {
                success: false,
                message: error.value
            };
        } finally {
            loading.value = false;
        }
    };

    /**
     * Lấy danh sách các ngân hàng được ZaloPay hỗ trợ
     */
    const getBanks = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await paymentService.getZaloPayBanks();
            return response;
        } catch (err: any) {
            error.value =
                err.data?.message ??
                err.message ??
                "Không thể lấy danh sách ngân hàng.";
            return {
                success: false,
                message: error.value
            };
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        initiatePayment,
        verifyPayment,
        getBanks
    };
}