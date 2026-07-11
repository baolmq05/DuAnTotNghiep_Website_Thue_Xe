import { BaseService } from "./base.service";

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
    location: string;
    address: string;
}

export interface DeliveryOption {
    id: number;
    max_distance: number;
    fee_distance: number;
    free_distance: number;
    status: number;
    created_at?: string;
    updated_at?: string;
}

export interface UsageLimit {
    id: number;
    max_daily_distance: number;
    extra_distance_fee: number;
    status: number;
    created_at?: string;
    updated_at?: string;
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
    VIN: string;
    engine_number: string;
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
    delivery_option?: DeliveryOption;
    usage_limit?: UsageLimit;
    car_brand?: CarBrand;
    car_type?: CarType;
    images?: CarImage[];
    features?: CarFeatureRegister[];
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

export interface TripExtension {
    id: number;
    trip_id: number;
    extension_amount: number | string;
    status: number; // 0: Chưa gia hạn, 1: Đã gửi yêu cầu, 2: Chờ thanh toán, 3: Đã gia hạn, 4: Bị từ chối
    start_date?: string;
    end_date?: string;
    created_at?: string;
    updated_at?: string;
}

export interface CarResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class CarService extends BaseService {
    constructor() {
        super("cars");
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
        return this.request<{ success: boolean; message: string; data: any }>(this.endpoint, {
            method: "POST",
            body: formData,
            useAuth: true
        });
    }

    /**
     * Cập nhật thông tin xe
     */
    async updateCar(id: string | number, payload: any): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`${this.endpoint}/${id}`, {
            method: "PUT",
            body: payload,
            useAuth: true
        });
    }

    /**
     * Tạo chuyến đi mới (Yêu cầu thuê xe)
     */
    async createTrip(payload: {
        cost: number;
        discount_amount: number;
        trip_type: number;
        start_at: string;
        end_at: string;
        car_id: number;
        delivery_address?: string;
        delivery_location?: string;
    }): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>("trips", {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Lấy danh sách chuyến đi của tôi (đã đặt & cho thuê)
     */
    async getTrips(): Promise<{ success: boolean; data: { booked: any[]; owner: any[] } }> {
        return this.request<{ success: boolean; data: { booked: any[]; owner: any[] } }>("trips", {
            method: "GET",
            useAuth: true,
        });
    }

    /**
     * Xác nhận cho thuê xe (Duyệt yêu cầu)
     */
    async confirmTrip(id: number | string): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/confirm`, {
            method: "PUT",
            useAuth: true,
        });
    }

    /**
     * Từ chối yêu cầu thuê xe
     */
    async rejectTrip(id: number | string, reason: string): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/reject`, {
            method: "PUT",
            body: { reason },
            useAuth: true,
        });
    }

    /**
     * Lấy thông tin chi tiết một chuyến đi
     */
    async getTripById(id: number | string): Promise<{ success: boolean; data: any }> {
        return this.request<{ success: boolean; data: any }>(`trips/${id}`, {
            method: "GET",
            useAuth: true,
        });
    }

    /**
     * Bắt đầu chuyến đi (tải ảnh lên và đổi trạng thái)
     */
    async startTrip(id: number | string, payload: any): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/start`, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Gửi yêu cầu gia hạn chuyến đi
     */
    async requestExtension(id: number | string, payload: { extended_days?: number; end_date?: string; start_date?: string; extension_amount?: number }): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/extension-request`, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Chủ xe duyệt yêu cầu gia hạn
     */
    async approveExtension(id: number | string): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/extension-approve`, {
            method: "PUT",
            useAuth: true,
        });
    }

    /**
     * Khách hàng thanh toán phí gia hạn
     */
    async payExtension(id: number | string): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/extension-pay`, {
            method: "POST",
            useAuth: true,
        });
    }

    /**
     * Chủ xe từ chối yêu cầu gia hạn
     */
    async rejectExtension(id: number | string, reason: string): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/extension-reject`, {
            method: "PUT",
            body: { reason },
            useAuth: true,
        });
    }

    /**
     * Hoàn thành chuyến đi (tải ảnh trả xe và đổi trạng thái)
     */
    async completeTrip(id: number | string, payload: { images: string[] }): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${id}/complete`, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Gửi đánh giá cho chuyến đi
     */
    async submitReview(tripId: number | string, payload: { rating: number; comment?: string }): Promise<{ success: boolean; message: string; data: any }> {
        return this.request<{ success: boolean; message: string; data: any }>(`trips/${tripId}/reviews`, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }
}

export const carService = new CarService();
