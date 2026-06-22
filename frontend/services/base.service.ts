import { API_URL } from "~/enviroment/enviroment";

export abstract class BaseService {
    protected endpoint: string;

    constructor(endpoint: string) {
        this.endpoint = endpoint;
    }

    protected getToken(): string | null {
        const tokenCookie = useCookie<string | null>("USER_TOKEN").value;
        if (tokenCookie) {
            return tokenCookie;
        }

        if (typeof window !== "undefined" && localStorage.getItem("USER_TOKEN")) {
            return localStorage.getItem("USER_TOKEN");
        }

        return null;
    }

    protected buildHeaders(useAuth: boolean = true): HeadersInit {
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

    protected async request<T>(
        url: string,
        options: {
            method?: "GET" | "POST" | "PUT" | "DELETE";
            body?: any;
            params?: Record<string, any>;
            useAuth?: boolean;
        } = {}
    ): Promise<T> {
        // Khởi tạo Cookies sớm trước bất kỳ await nào để tránh mất ngữ cảnh Nuxt (Nuxt instance not available)
        const tokenCookie = useCookie<string | null>("USER_TOKEN", { maxAge: 60 * 60 * 24 * 7, path: '/' });
        const userCookie = useCookie<any>("USER_INFO", { maxAge: 60 * 60 * 24 * 7, path: '/' });

        try {
            const { method = "GET", body, params, useAuth = true } = options;

            const headers = { ...this.buildHeaders(useAuth) } as any;
            if (body instanceof FormData) {
                delete headers["Content-Type"];
            }

            // Tự động dọn dẹp các tham số truy vấn trống
            let query: Record<string, any> | undefined = undefined;
            if (params) {
                query = Object.entries(params)
                    .filter(([_, value]) => value !== undefined && value !== null && value !== "")
                    .reduce((acc, [key, value]) => ({ ...acc, [key]: value }), {});
            }

            return await $fetch<T>(`${API_URL}${url}`, {
                method,
                body,
                query,
                headers
            });
        } catch (err: any) {
            const token = tokenCookie.value || (typeof window !== "undefined" ? localStorage.getItem("USER_TOKEN") : null);
            const { method = "GET", body, useAuth = true } = options;

            // Nếu gặp lỗi 401, tài khoản có token và request không phải là login/refresh -> tiến hành refresh token
            if (useAuth && err.response && err.response.status === 401 && token && !url.includes("auth/refresh") && !url.includes("auth/login")) {
                try {
                    const refreshRes = await $fetch<any>(`${API_URL}auth/refresh`, {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${token}`
                        }
                    });

                    if (refreshRes && refreshRes.access_token) {
                        // Cập nhật token và thông tin user mới
                        tokenCookie.value = refreshRes.access_token;
                        userCookie.value = refreshRes.user || null;

                        if (typeof window !== "undefined") {
                            localStorage.setItem("USER_TOKEN", refreshRes.access_token);
                            if (refreshRes.user) {
                                localStorage.setItem("USER_INFO", JSON.stringify(refreshRes.user));
                            }
                        }

                        // Thực hiện lại yêu cầu ban đầu với token mới
                        const retryHeaders = {
                            "Content-Type": "application/json",
                            "Authorization": `Bearer ${refreshRes.access_token}`
                        } as any;
                        if (body instanceof FormData) {
                            delete retryHeaders["Content-Type"];
                        }

                        let query: Record<string, any> | undefined = undefined;
                        if (options.params) {
                            query = Object.entries(options.params)
                                .filter(([_, value]) => value !== undefined && value !== null && value !== "")
                                .reduce((acc, [key, value]) => ({ ...acc, [key]: value }), {});
                        }

                        return await $fetch<T>(`${API_URL}${url}`, {
                            method,
                            body,
                            query,
                            headers: retryHeaders
                        });
                    }
                } catch (refreshErr) {
                    console.error("[Token Refresh Failed]", refreshErr);
                    // Xóa sạch thông tin đăng nhập khi refresh thất bại
                    tokenCookie.value = null;
                    userCookie.value = null;
                    if (typeof window !== "undefined") {
                        localStorage.removeItem("USER_TOKEN");
                        localStorage.removeItem("USER_INFO");
                        window.location.href = "/login";
                    }
                    throw refreshErr;
                }
            }

            console.error(`[API ERROR] ${url}`, err);
            throw err;
        }
    }

    async getAll<T>(useAuth: boolean = true): Promise<T[]> {
        return this.request<T[]>(this.endpoint, { useAuth });
    }

    async getById<T>(id: string, useAuth: boolean = true): Promise<T> {
        return this.request<T>(`${this.endpoint}/${id}`, { useAuth });
    }

    async create<T>(payload: any, useAuth: boolean = true): Promise<T> {
        return this.request<T>(this.endpoint, {
            method: "POST",
            body: payload,
            useAuth
        });
    }

    async update<T>(id: string, payload: any, useAuth: boolean = true): Promise<T> {
        return this.request<T>(`${this.endpoint}/${id}`, {
            method: "PUT",
            body: payload,
            useAuth
        });
    }

    async delete(id: string, useAuth: boolean = true): Promise<void> {
        return this.request<void>(`${this.endpoint}/${id}`, {
            method: "DELETE",
            useAuth
        });
    }
}