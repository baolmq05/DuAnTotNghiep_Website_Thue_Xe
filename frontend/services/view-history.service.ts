import { BaseService } from "./base.service";
import type { Car } from "./car.service";

export interface ViewHistoryItem {
    id: number;
    user_id: number;
    car_id: number;
    created_at: string;
    updated_at: string;
    car?: Car;
}

export interface ViewHistoryResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class ViewHistoryService extends BaseService {
    constructor() {
        super("auth/view-histories");
    }

    /**
     * Ghi nhận lượt xem xe vào DB
     */
    async recordViewHistory(carId: number | string): Promise<ViewHistoryResponse<any>> {
        return this.request<ViewHistoryResponse<any>>(this.endpoint, {
            method: "POST",
            body: { car_id: carId },
            useAuth: true,
        });
    }

    /**
     * Lấy danh sách lịch sử xe đã xem của user
     */
    async getViewHistory(params?: { limit?: number; page?: number }): Promise<ViewHistoryResponse<any>> {
        return this.request<ViewHistoryResponse<any>>(this.endpoint, {
            method: "GET",
            params,
            useAuth: true,
        });
    }

    /**
     * Xóa 1 xe khỏi lịch sử xem
     */
    async deleteViewHistory(carId: number | string): Promise<ViewHistoryResponse<any>> {
        return this.request<ViewHistoryResponse<any>>(`${this.endpoint}/${carId}`, {
            method: "DELETE",
            useAuth: true,
        });
    }
}

export const viewHistoryService = new ViewHistoryService();
