import { BaseService } from "./base.service";

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

export class AddressService extends BaseService {
    constructor() {
        super("auth/addresses");
    }

    /**
     * Lấy danh sách địa chỉ của người dùng theo user_id
     */
    async getAddresses(userId?: number | string): Promise<AddressResponse<Address[]>> {
        return this.request<AddressResponse<Address[]>>(this.endpoint, {
            method: "GET",
            params: { user_id: userId },
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
