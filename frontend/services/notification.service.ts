import { BaseService } from "./base.service";

export interface NotificationItem {
    id?: number;
    message: string;
    is_read: "0" | "1";
    user_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface NotificationResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class NotificationService extends BaseService {
    constructor() {
        super("auth/notifications");
    }

    /**
     * Lấy danh sách thông báo của người dùng theo user_id
     */
    async getNotifications(userId?: number | string): Promise<NotificationResponse<NotificationItem[]>> {
        return this.request<NotificationResponse<NotificationItem[]>>(this.endpoint, {
            method: "GET",
            params: { user_id: userId },
            useAuth: true,
        });
    }

    /**
     * Tạo thông báo mới
     */
    async createNotification(payload: { message: string; user_id: number }): Promise<NotificationResponse<NotificationItem>> {
        return this.request<NotificationResponse<NotificationItem>>(this.endpoint, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Cập nhật trạng thái thông báo
     */
    async updateNotification(id: number | string, payload: { is_read: "0" | "1" }): Promise<NotificationResponse<NotificationItem>> {
        return this.request<NotificationResponse<NotificationItem>>(`${this.endpoint}/${id}`, {
            method: "PUT",
            body: payload,
            useAuth: true,
        });
    }

    /**
     * Đánh dấu tất cả thông báo là đã đọc
     */
    async markAllRead(): Promise<NotificationResponse<any>> {
        return this.request<NotificationResponse<any>>(`${this.endpoint}/read-all`, {
            method: "PUT",
            useAuth: true,
        });
    }

    /**
     * Xóa thông báo
     */
    async deleteNotification(id: number | string): Promise<NotificationResponse<any>> {
        return this.request<NotificationResponse<any>>(`${this.endpoint}/${id}`, {
            method: "DELETE",
            useAuth: true,
        });
    }
}

export const notificationService = new NotificationService();
