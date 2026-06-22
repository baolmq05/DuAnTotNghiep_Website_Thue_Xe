import { BaseService } from "./base.service";
import type { Car } from "./car.service";

export interface FavoriteItem {
    id: number;
    car_id: number;
    car_name: string;
    created_at: string;
    car?: Car;
}

export interface FavoriteResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class FavoriteService extends BaseService {
    constructor() {
        super("favorites");
    }

    async getFavorites(): Promise<FavoriteResponse<FavoriteItem[]>> {
        return this.request<FavoriteResponse<FavoriteItem[]>>(this.endpoint, {
            method: "GET",
            useAuth: true,
        });
    }

    async addFavorite(carId: number | string): Promise<FavoriteResponse<any>> {
        return this.request<FavoriteResponse<any>>(this.endpoint, {
            method: "POST",
            body: { car_id: carId },
            useAuth: true,
        });
    }

    async removeFavorite(carId: number | string): Promise<FavoriteResponse<any>> {
        return this.request<FavoriteResponse<any>>(`${this.endpoint}/${carId}`, {
            method: "DELETE",
            useAuth: true,
        });
    }
}

export const favoriteService = new FavoriteService();
