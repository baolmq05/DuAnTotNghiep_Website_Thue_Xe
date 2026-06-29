import { BaseService } from "./base.service";

export interface PromotionImage {
    id: number;
    image_url: string;
    promotion_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface Promotion {
    id: number;
    code: string;
    name: string;
    description: string;
    discount_type: string;
    discount_value: number;
    start_date: string;
    end_date: string;
    usage_limit?: number;
    per_user_limit?: number;
    status: string;
    user_id?: number;
    created_at?: string;
    updated_at?: string;
    images?: PromotionImage[];
}

export interface PromotionResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class PromotionService extends BaseService {
    constructor() {
        super("promotions");
    }

    async getPromotions(): Promise<PromotionResponse<Promotion[]>> {
        return this.request<PromotionResponse<Promotion[]>>(this.endpoint, {
            method: "GET",
            useAuth: false,
        });
    }
}

export const promotionService = new PromotionService();
