import { API_URL } from "~/enviroment/enviroment";

export interface Address {
    id?: number;
    address_name: string;
    user_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface AddressResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class AddressService {
    private endpoint = "auth/addresses";

    private getToken(): string | null {
        // Thử lấy token từ Cookie trước (hoạt động được cả ở Server SSR và Client)
        const tokenCookie = useCookie<string | null>("USER_TOKEN").value;
        if (tokenCookie) {
            return tokenCookie;
        }
        // Fallback lấy từ localStorage ở phía Client
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
            method?: "GET" | "POST" | "PUT" | "DELETE";
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

    /**
     * Lấy danh sách địa chỉ của người dùng theo user_id
     */
    async getAddresses(userId?: number | string): Promise<AddressResponse<Address[]>> {
        const url = userId ? `${this.endpoint}?user_id=${userId}` : this.endpoint;
        return this.request<AddressResponse<Address[]>>(url, {
            method: "GET",
            useAuth: true,
        });
    }

    /**
     * Thêm địa chỉ mới
     */
    async createAddress(payload: { address_name: string; user_id: number }): Promise<AddressResponse<Address>> {
        return this.request<AddressResponse<Address>>(this.endpoint, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Cập nhật địa chỉ
     */
    async updateAddress(id: number | string, payload: { address_name: string }): Promise<AddressResponse<Address>> {
        return this.request<AddressResponse<Address>>(`${this.endpoint}/${id}`, {
            method: "PUT",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Xóa địa chỉ
     */
    async deleteAddress(id: number | string): Promise<AddressResponse<any>> {
        return this.request<AddressResponse<any>>(`${this.endpoint}/${id}`, {
            method: "DELETE",
            useAuth: true,
        });
    }
}

export const addressService = new AddressService();
