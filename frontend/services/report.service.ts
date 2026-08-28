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

export interface OwnerPenaltyItem {
    id: number;
    penalty_type: number; // 0: Warning1, 1: Warning2, 2: AccountSuspension
    penalty_type_text: string;
    reason: string;
    start_at: string | null;
    end_at: string | null;
    is_active: boolean;
    trip_id?: number | null;
    trip_code?: string | null;
    report_id?: number | null;
    created_at: string;
}

export interface OwnerReportItem {
    id: number;
    report_type: number;
    report_type_text: string;
    title: string;
    description: string;
    status: number; // 0: Pending, 1: Resolved, 2: Rejected, 3: Cancelled
    status_text: string;
    admin_note?: string | null;
    resolved_at?: string | null;
    created_at: string;
    updated_at?: string;
    trip?: {
        id: number;
        trip_code: string;
        start_at?: string;
        end_at?: string;
        cost?: number;
        discount_amount?: number;
        delivery_address?: string;
        delivery_location?: string;
        status?: number;
        status_text?: string;
        renter?: {
            id: number;
            name: string;
            avatar?: string;
            phone?: string;
            email?: string;
        };
    } | null;
    car?: {
        id: number;
        name: string;
        license_plate: string;
        brand_name?: string;
        type_name?: string;
        thumbnail?: string;
        seat_count?: number;
        manufacture_year?: number;
        fuel_type?: string;
        transmission?: string;
        images?: Array<{ id: number; image_url: string }>;
    } | null;
    reporter?: {
        id: number;
        name: string;
        avatar?: string;
        phone?: string;
        email?: string;
    } | null;
    resolver?: {
        id: number;
        name: string;
    } | null;
    images: Array<{
        id: number;
        image_url: string;
    }>;
    penalty?: OwnerPenaltyItem | null;
}

export interface OwnerReportSummary {
    account_status: "ACTIVE" | "SUSPENDED" | string;
    is_account_suspended: boolean;
    active_strikes: number;
    total_strikes: number;
    reports: {
        total: number;
        pending: number;
        resolved: number;
        rejected: number;
        cancelled: number;
    };
    penalties_breakdown: {
        warnings: number;
        car_suspensions: number;
        account_suspensions: number;
    };
    active_penalties: OwnerPenaltyItem[];
    recent_reports: OwnerReportItem[];
}

export interface OwnerReportsPagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

export interface OwnerReportsResponse {
    success: boolean;
    data: OwnerReportItem[];
    pagination: OwnerReportsPagination;
}

export interface OwnerReportDetailResponse {
    success: boolean;
    data: OwnerReportItem;
}

export interface OwnerReportSummaryResponse {
    success: boolean;
    data: OwnerReportSummary;
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

    // --- Owner Report & Strike Methods ---
    async getOwnerSummary(): Promise<OwnerReportSummaryResponse> {
        return this.request<OwnerReportSummaryResponse>("owner/reports/summary", {
            method: "GET",
            useAuth: true,
        });
    }

    async getOwnerReports(params: {
        status?: number | string;
        search?: string;
        page?: number;
        per_page?: number;
    } = {}): Promise<OwnerReportsResponse> {
        return this.request<OwnerReportsResponse>("owner/reports", {
            method: "GET",
            params,
            useAuth: true,
        });
    }

    async getOwnerReportDetail(id: number | string): Promise<OwnerReportDetailResponse> {
        return this.request<OwnerReportDetailResponse>(`owner/reports/${id}`, {
            method: "GET",
            useAuth: true,
        });
    }
}

export const reportService = new ReportService();
