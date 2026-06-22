import { API_URL } from "~/enviroment/enviroment";

class ChatBotService {
    private endpoint = "auth/chatbot";

    async getMessages(): Promise<any> {
        return await $fetch<any>(`${API_URL}${this.endpoint}`, {
            method: "GET",
            headers: this.buildHeaders()
        });
    }

    async sendMessage(conversationId?: string, message?: string): Promise<any> {
        return await $fetch<any>(`${API_URL}${this.endpoint}`, {
            method: "POST",
            headers: this.buildHeaders(),
            body: {
                conversationId,
                message
            }
        });
    }

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
}

export const chatBotService = new ChatBotService();