import { BaseService } from "./base.service";

export interface ReportPayload {
    trip_id: number;
    report_type: number;
    description: string;
    images?: string[];
}

export interface ReportResponse {
    id: number;
    trip_id: number;
    reporter_id: number;
    report_type: number;
    title: string;
    description: string;
    status: number;
    created_at: string;
    updated_at: string;
    images?: Array<{
        id: number;
        report_id: number;
        image_url: string;
    }>;
}

export interface ApiResponse<T> {
    success: boolean;
    message: string;
    data: T;
}

export class ReportService extends BaseService {
    constructor() {
        super("reports");
    }

    async createReport(payload: ReportPayload): Promise<ApiResponse<ReportResponse>> {
        return this.request<ApiResponse<ReportResponse>>(this.endpoint, {
            method: "POST",
            body: payload,
            useAuth: true,
        });
    }

    async revokeReport(id: number): Promise<ApiResponse<ReportResponse>> {
        return this.request<ApiResponse<ReportResponse>>(`${this.endpoint}/${id}/revoke`, {
            method: "POST",
            useAuth: true,
        });
    }
}

export const reportService = new ReportService();
