import { API_URL } from "~/enviroment/enviroment";

export interface CarBrand {
    id: number;
    brand_name: string;
    created_at?: string;
    updated_at?: string;
}

export interface CarType {
    id: number;
    type_name: string;
    car_brand_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface CarFeature {
    id: number;
    feature_name: string;
    icon: string;
    description: string;
    status: number;
    created_at?: string;
    updated_at?: string;
}

export class CarService {
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

    private buildHeaders(useAuth: boolean = true, isMultipart: boolean = false): HeadersInit {
        const headers: HeadersInit = {};

        if (!isMultipart) {
            headers["Content-Type"] = "application/json";
        }

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
            isMultipart?: boolean;
        } = {}
    ): Promise<T> {
        try {
            const { method = "GET", body, useAuth = true, isMultipart = false } = options;

            return await $fetch<T>(`${API_URL}${url}`, {
                method,
                body,
                headers: this.buildHeaders(useAuth, isMultipart)
            });
        } catch (err) {
            console.error(`[API ERROR] ${url}`, err);
            throw err;
        }
    }

    /**
     * Lấy danh sách hãng xe
     */
    async getBrands(): Promise<{ success: boolean; data: CarBrand[] }> {
        return this.request<{ success: boolean; data: CarBrand[] }>("car-brands", {
            method: "GET",
            useAuth: false,
        });
    }

    /**
     * Lấy danh sách dòng xe theo hãng
     */
    async getTypes(brandId: number | string): Promise<{ success: boolean; data: CarType[] }> {
        return this.request<{ success: boolean; data: CarType[] }>(`car-brands/${brandId}/types`, {
            method: "GET",
            useAuth: false,
        });
    }

    /**
     * Lấy danh sách tính năng xe
     */
    async getFeatures(): Promise<{ success: boolean; data: CarFeature[] }> {
        return this.request<{ success: boolean; data: CarFeature[] }>("car-features", {
            method: "GET",
            useAuth: false,
        });
    }

    /**
     * Đăng ký xe mới
     */
    async registerCar(formData: FormData): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>("cars", {
            method: "POST",
            body: formData,
            useAuth: true,
            isMultipart: true,
        });
    }
}

export const carService = new CarService();
