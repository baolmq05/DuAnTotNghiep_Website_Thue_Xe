import { BaseService } from "./base.service";

export interface TransactionDetail {
    id: number;
    transaction_code: string;
    amount: number;
    prepay: number;
    created_at: string;
    trip?: {
        id: number;
        start_at?: string;
        end_at?: string;
        created_at?: string;
        cost: number;
        discount_amount: number;
        status: number;
        customer_name: string;
        car?: {
            id: number;
            name: string;
            license_plate: string;
            unit_price: number;
        };
    } | null;
}

export interface WalletSummary {
    completed_trips_change: number;
    deposit_withdrawal_change: number;
    cancelled_trips_change: number;
    total_change: number;
    start_balance: number;
    end_balance: number;
    tax_deducted: number;
    owner_income: number;
}

export interface RefundDetail {
    id: number;
    transaction_code: string;
    amount: number;
    status: string;
    description: string;
    created_at: string | null;
}

export interface WalletData {
    balance: number;
    hold_balance?: number;
    rating: number;
    completed_trips_count: number;
    response_rate: number;
    response_time: string;
    accept_rate: number;
    transactions: TransactionDetail[];
    refunds?: RefundDetail[];
    summary: WalletSummary;
}

export interface WalletResponse {
    success: boolean;
    data: WalletData;
    message?: string;
}

export class WalletService extends BaseService {
    constructor() {
        super("auth/wallet");
    }

    async getWalletDetails(params?: { month?: number; year?: number }): Promise<WalletResponse> {
        return this.request<WalletResponse>(this.endpoint, {
            method: "GET",
            params,
            useAuth: true
        });
    }

    async withdraw(amount: number, description?: string): Promise<any> {
        return this.request<any>(`${this.endpoint}/withdraw`, {
            method: "POST",
            body: { amount, description },
            useAuth: true
        });
    }

    async withdrawHold(amount: number, description?: string): Promise<any> {
        return this.request<any>(`${this.endpoint}/withdraw-hold`, {
            method: "POST",
            body: { amount, description },
            useAuth: true
        });
    }
}

export const walletService = new WalletService();
