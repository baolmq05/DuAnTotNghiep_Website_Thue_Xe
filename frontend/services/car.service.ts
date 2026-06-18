import { API_URL } from "~/enviroment/enviroment";

export interface CarImage {
    id: number;
    car_id: number;
    image_url: string;
    is_thumbnail: number;
    created_at?: string;
    updated_at?: string;
}

export interface CarBrand {
    id: number;
    name: string;
    logo_url?: string;
}

export interface CarType {
    id: number;
    name: string;
}

export interface CarLocation {
    id: number;
    street_name: string;
    ward_name?: string;
    district_name?: string;
    city_name?: string;
}

export interface CarBrandRegister {
    id: number;
    brand_name: string;
    created_at?: string;
    updated_at?: string;
}

export interface CarTypeRegister {
    id: number;
    type_name: string;
    car_brand_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface CarFeatureRegister {
    id: number;
    feature_name: string;
    icon: string;
    description: string;
    status: number;
    created_at?: string;
    updated_at?: string;
}

export interface Car {
    id: number;
    name: string;
    license_plate: string;
    fuel_consumption: number;
    unit_price: number;
    discount_value: number;
    description?: string;
    rental_terms?: string;
    car_location_id: number;
    car_brand_id: number;
    car_type_id: number;
    seat_count: number;
    manufacture_year: string;
    fuel_type: string;
    transmission: string;
    user_id: number;
    delivery_option_id?: number;
    usage_limit_id?: number;
    status: number;
    created_at?: string;
    updated_at?: string;
    car_location?: CarLocation;
    car_brand?: CarBrand;
    car_type?: CarType;
    images?: CarImage[];
    reviews_avg_rating?: string | number | null;
    trips_count?: number;
    owner?: {
        id: number;
        name: string;
        avatar?: string;
        phone?: string;
        gender?: number;
    };
}

export interface CarResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class CarService {
    private endpoint = "cars";

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
            params?: Record<string, any>;
            useAuth?: boolean;
            isMultipart?: boolean;
        } = {}
    ): Promise<T> {
        try {
            const { method = "GET", body, params, useAuth = false, isMultipart = false } = options;

            // Build query parameters
            let queryString = "";
            if (params) {
                const activeParams = Object.entries(params)
                    .filter(([_, value]) => value !== undefined && value !== null && value !== "")
                    .reduce((acc, [key, value]) => ({ ...acc, [key]: value }), {});
                
                if (Object.keys(activeParams).length > 0) {
                    queryString = "?" + new URLSearchParams(activeParams as any).toString();
                }
            }

            return await $fetch<T>(`${API_URL}${url}${queryString}`, {
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
     * Lấy danh sách xe (có hỗ trợ lọc qua query params)
     */
    async getCars(params?: {
        startDate?: string;
        endDate?: string;
        address?: string;
        brand_id?: number | string;
        type_id?: number | string;
        seat_count?: number | string;
        min_price?: number | string;
        max_price?: number | string;
    }): Promise<CarResponse<Car[]>> {
        return this.request<CarResponse<Car[]>>(this.endpoint, {
            method: "GET",
            params,
            useAuth: false,
        });
    }

    /**
     * Lấy thông tin chi tiết của 1 xe
     */
    async getCarById(id: string | number): Promise<CarResponse<Car>> {
        return this.request<CarResponse<Car>>(`${this.endpoint}/${id}`, {
            method: "GET",
            useAuth: false,
        });
    }

    /**
     * Lấy danh sách hãng xe
     */
    async getBrands(): Promise<{ success: boolean; data: CarBrandRegister[] }> {
        return this.request<{ success: boolean; data: CarBrandRegister[] }>("car-brands", {
            method: "GET",
            useAuth: false,
        });
    }

    /**
     * Lấy danh sách dòng xe theo hãng
     */
    async getTypes(brandId: number | string): Promise<{ success: boolean; data: CarTypeRegister[] }> {
        return this.request<{ success: boolean; data: CarTypeRegister[] }>(`car-brands/${brandId}/types`, {
            method: "GET",
            useAuth: false,
        });
    }

    /**
     * Lấy danh sách tính năng xe
     */
    async getFeatures(): Promise<{ success: boolean; data: CarFeatureRegister[] }> {
        return this.request<{ success: boolean; data: CarFeatureRegister[] }>("car-features", {
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
