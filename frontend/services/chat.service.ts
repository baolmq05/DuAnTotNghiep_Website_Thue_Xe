import { BaseService } from "./base.service";

export class ChatService extends BaseService {
    constructor() {
        super("");
    }

    public async getConversations() {
        return this.request<any>("conversations", {
            method: "GET",
            useAuth: true
        });
    }

    public async storeConversation(payload: { trip_id: string | number }) {
        return this.request<any>("conversations", {
            method: "POST",
            body: payload,
            useAuth: true
        });
    }

    public async getMessagesByConversationId(id: string | number) {
        return this.request<any>(`messages/${id}`, {
            method: "GET",
            useAuth: true
        });
    }

    public async storeMessage(payload: { conversation_id: string | number; text: string; type: string }) {
        return this.request<any>("messages", {
            method: "POST",
            body: payload,
            useAuth: true
        });
    }

    public async markAsRead(id: string | number) {
        return this.request<any>(`conversations/${id}/read`, {
            method: "PUT",
            useAuth: true
        });
    }
}