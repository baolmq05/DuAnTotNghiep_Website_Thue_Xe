import { BaseService } from "./base.service";

class ChatBotService extends BaseService {
    constructor() {
        super("auth/chatbot");
    }

    async getMessages(): Promise<any> {
        return this.request<any>(this.endpoint, {
            method: "GET",
            useAuth: true
        });
    }

    async sendMessage(conversationId?: string, message?: string): Promise<any> {
        return this.request<any>(this.endpoint, {
            method: "POST",
            body: {
                conversationId,
                message
            },
            useAuth: true
        });
    }
}

export const chatBotService = new ChatBotService();