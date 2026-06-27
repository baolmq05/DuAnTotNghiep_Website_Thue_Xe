import { BaseService } from "./base.service";

export interface ZaloPayCreateResponse {
    success: boolean;
    payment_url?: string;
    message?: string;
    zalopay?: any;
}

export interface VNPayCreateResponse {
    success: boolean;
    payment_url?: string;
    message?: string;
}

export interface PaymentVerifyResponse {
    success: boolean;
    message: string;
    data?: any;
}

export class PaymentService extends BaseService {
    constructor() {
        super("");
    }

    async createZaloPayPayment(
        amount: number,
        paymentType: "rental" | "deposit" | "penalty",
        tripId?: number
    ): Promise<ZaloPayCreateResponse> {
        return this.request<ZaloPayCreateResponse>("auth/zalopay/create-payment", {
            method: "POST",
            body: {
                payment_type: paymentType,
                amount,
                trip_id: tripId
            },
            useAuth: true
        });
    }

    async createVNPayPayment(
        amount: number,
        paymentType: "rental" | "deposit" | "penalty",
        tripId?: number
    ): Promise<VNPayCreateResponse> {
        return this.request<VNPayCreateResponse>("auth/vnpay/create-payment", {
            method: "POST",
            body: {
                payment_type: paymentType,
                amount,
                trip_id: tripId
            },
            useAuth: true
        });
    }

    async verifyZaloPayPayment(appTransId: string): Promise<PaymentVerifyResponse> {
        return this.request<PaymentVerifyResponse>(`zalopay/verify`, {
            method: "GET",
            params: { app_trans_id: appTransId },
            useAuth: false
        });
    }

    async verifyVNPayPayment(queryParams: Record<string, any>): Promise<PaymentVerifyResponse> {
        return this.request<PaymentVerifyResponse>(`vnpay/verify`, {
            method: "GET",
            params: queryParams,
            useAuth: false
        });
    }

    async getZaloPayBanks(): Promise<{ success: boolean; banks?: any; message?: string }> {
        return this.request<{ success: boolean; banks?: any; message?: string }>("zalopay/banks", {
            method: "GET",
            useAuth: false
        });
    }
}

export const paymentService = new PaymentService();
