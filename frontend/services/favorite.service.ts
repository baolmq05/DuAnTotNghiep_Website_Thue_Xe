import { API_URL } from "~/enviroment/enviroment";
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

export class FavoriteService {
    private endpoint = "favorites";

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
