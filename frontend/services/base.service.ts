import { API_URL } from "~/enviroment/enviroment";

export abstract class BaseService {
    protected endpoint: string;

    constructor(endpoint: string) {
        this.endpoint = endpoint;
    }

    protected getToken(): string | null {
        if (localStorage.getItem("USER_TOKEN")) {
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