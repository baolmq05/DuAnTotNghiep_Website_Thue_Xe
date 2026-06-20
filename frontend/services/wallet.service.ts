import { API_URL } from "~/enviroment/enviroment";

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

export interface WalletData {
    balance: number;
    rating: number;
    completed_trips_count: number;
    response_rate: number;
    response_time: string;
    accept_rate: number;
    transactions: TransactionDetail[];
    summary: WalletSummary;
}

export interface WalletResponse {
    success: boolean;
    data: WalletData;
    message?: string;
}

export class WalletService {
    private endpoint = "auth/wallet";

    private getToken(): string | null {
        const tokenCookie = useCookie<string | null>("USER_TOKEN").value;
        if (tokenCookie) {
            return tokenCookie;
        }
        if (typeof window !== "undefined" && localStorage.getItem("USER_TOKEN")) {
            return localStorage.getItem("USER_TOKEN");
        }
        return null;
    }

    private buildHeaders(useAuth: boolean = true): HeadersInit {
        const headers: HeadersInit = {
            "Content-Type": "application/json"
        };

        if (useAuth) {
            const token = this.getToken();
            if (token) {
                headers["Authorization"] = `Bearer ${token}`;
            }
        }

        return headers;
    }

    private async request<T>(
        url: string,
        options: {
            method?: "GET" | "POST";
            body?: any;
            useAuth?: boolean;
        } = {}
    ): Promise<T> {
        try {
            const { method = "GET", body, useAuth = true } = options;

            return await $fetch<T>(`${API_URL}${url}`, {
                method,
                body,
                headers: this.buildHeaders(useAuth)
            });
        } catch (err) {
            console.error(`[API ERROR] ${url}`, err);
            throw err;
        }
    }

    async getWalletDetails(): Promise<WalletResponse> {
        return this.request<WalletResponse>(this.endpoint, {
            method: "GET",
            useAuth: true
        });
    }
}

export const walletService = new WalletService();
