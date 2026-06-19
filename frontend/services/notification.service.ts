import { API_URL } from "~/enviroment/enviroment";

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

export class NotificationService {
    private endpoint = "auth/notifications";

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
     * Lấy danh sách thông báo của người dùng theo user_id
     */
    async getNotifications(userId?: number | string): Promise<NotificationResponse<NotificationItem[]>> {
        const url = userId ? `${this.endpoint}?user_id=${userId}` : this.endpoint;
        return this.request<NotificationResponse<NotificationItem[]>>(url, {
            method: "GET",
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
