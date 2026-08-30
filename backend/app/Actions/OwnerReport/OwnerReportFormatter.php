<?php

namespace App\Actions\OwnerReport;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Enum\TripStatus;
use App\Models\OwnerPenalty;
use App\Models\Report;
use Carbon\Carbon;

class OwnerReportFormatter
{
    /**
     * Format a report for listing item view.
     */
    public static function formatReportListItem(Report $report): array
    {
        $statusEnum = $report->status instanceof ReportStatus ? $report->status : ReportStatus::tryFrom((int) $report->status);
        $typeEnum = $report->report_type instanceof ReportType ? $report->report_type : ReportType::tryFrom((int) $report->report_type);

        $trip = $report->trip;
        $car = $trip?->car;
        $reporter = $report->reporter;

        return [
            'id' => $report->id,
            'report_type' => $typeEnum ? $typeEnum->value : (int) $report->report_type,
            'report_type_text' => $typeEnum ? $typeEnum->getLabel() : 'Không xác định',
            'title' => $report->title,
            'description' => $report->description,
            'status' => $statusEnum ? $statusEnum->value : (int) $report->status,
            'status_text' => $statusEnum ? $statusEnum->getLabel() : 'Không xác định',
            'admin_note' => $report->admin_note,
            'resolved_at' => $report->resolved_at?->toIso8601String(),
            'created_at' => $report->created_at?->toIso8601String(),
            'trip' => $trip ? [
                'id' => $trip->id,
                'trip_code' => $trip->trip_code,
                'start_at' => $trip->start_at,
                'end_at' => $trip->end_at,
                'cost' => $trip->cost,
                'status' => $trip->status instanceof TripStatus ? $trip->status->value : (int) $trip->status,
                'status_text' => self::getTripStatusText($trip->status),
            ] : null,
            'car' => $car ? [
                'id' => $car->id,
                'name' => $car->name,
                'license_plate' => $car->license_plate,
                'brand_name' => $car->carBrand?->brand_name ?? $car->carBrand?->name ?? null,
                'type_name' => $car->carType?->type_name ?? $car->carType?->name ?? null,
                'thumbnail' => $car->images?->first()?->image_url ?? null,
            ] : null,
            'reporter' => $reporter ? [
                'id' => $reporter->id,
                'name' => $reporter->name,
                'avatar' => $reporter->avatar,
                'phone' => $reporter->phone,
                'email' => $reporter->email,
            ] : null,
            'images' => $report->images->map(function ($img) {
                return [
                    'id' => $img->id,
                    'image_url' => $img->image_url,
                ];
            }),
            'penalty' => $report->penalty ? self::formatPenalty($report->penalty) : null,
        ];
    }

    /**
     * Format a report for full detail view.
     */
    public static function formatReportDetail(Report $report): array
    {
        $statusEnum = $report->status instanceof ReportStatus ? $report->status : ReportStatus::tryFrom((int) $report->status);
        $typeEnum = $report->report_type instanceof ReportType ? $report->report_type : ReportType::tryFrom((int) $report->report_type);

        $trip = $report->trip;
        $car = $trip?->car;
        $reporter = $report->reporter;
        $resolver = $report->resolver;

        return [
            'id' => $report->id,
            'report_type' => $typeEnum ? $typeEnum->value : (int) $report->report_type,
            'report_type_text' => $typeEnum ? $typeEnum->getLabel() : 'Không xác định',
            'title' => $report->title,
            'description' => $report->description,
            'status' => $statusEnum ? $statusEnum->value : (int) $report->status,
            'status_text' => $statusEnum ? $statusEnum->getLabel() : 'Không xác định',
            'admin_note' => $report->admin_note,
            'resolved_at' => $report->resolved_at?->toIso8601String(),
            'created_at' => $report->created_at?->toIso8601String(),
            'updated_at' => $report->updated_at?->toIso8601String(),
            'trip' => $trip ? [
                'id' => $trip->id,
                'trip_code' => $trip->trip_code,
                'start_at' => $trip->start_at,
                'end_at' => $trip->end_at,
                'cost' => $trip->cost,
                'discount_amount' => $trip->discount_amount,
                'delivery_address' => $trip->delivery_address,
                'delivery_location' => $trip->delivery_location,
                'status' => $trip->status instanceof TripStatus ? $trip->status->value : (int) $trip->status,
                'status_text' => self::getTripStatusText($trip->status),
                'renter' => $trip->user ? [
                    'id' => $trip->user->id,
                    'name' => $trip->user->name,
                    'avatar' => $trip->user->avatar,
                    'phone' => $trip->user->phone,
                    'email' => $trip->user->email,
                ] : null,
            ] : null,
            'car' => $car ? [
                'id' => $car->id,
                'name' => $car->name,
                'license_plate' => $car->license_plate,
                'brand_name' => $car->carBrand?->brand_name ?? $car->carBrand?->name ?? null,
                'type_name' => $car->carType?->type_name ?? $car->carType?->name ?? null,
                'seat_count' => $car->seat_count,
                'manufacture_year' => $car->manufacture_year,
                'fuel_type' => $car->fuel_type,
                'transmission' => $car->transmission,
                'images' => $car->images->map(fn ($img) => [
                    'id' => $img->id,
                    'image_url' => $img->image_url,
                ]),
            ] : null,
            'reporter' => $reporter ? [
                'id' => $reporter->id,
                'name' => $reporter->name,
                'avatar' => $reporter->avatar,
                'phone' => $reporter->phone,
                'email' => $reporter->email,
            ] : null,
            'resolver' => $resolver ? [
                'id' => $resolver->id,
                'name' => $resolver->name,
            ] : null,
            'images' => $report->images->map(function ($img) {
                return [
                    'id' => $img->id,
                    'image_url' => $img->image_url,
                ];
            }),
            'penalty' => $report->penalty ? self::formatPenalty($report->penalty) : null,
        ];
    }

    /**
     * Format a penalty object.
     */
    public static function formatPenalty(OwnerPenalty $penalty): array
    {
        $penaltyTypeEnum = $penalty->penalty_type instanceof PenaltyType ? $penalty->penalty_type : PenaltyType::tryFrom((int) $penalty->penalty_type);

        $isActive = ($penalty->end_at === null || $penalty->end_at->isFuture()) &&
                    ($penalty->start_at === null || $penalty->start_at->isPast());

        return [
            'id' => $penalty->id,
            'penalty_type' => $penaltyTypeEnum ? $penaltyTypeEnum->value : (int) $penalty->penalty_type,
            'penalty_type_text' => $penaltyTypeEnum ? $penaltyTypeEnum->getLabel() : 'Không xác định',
            'reason' => $penalty->reason,
            'start_at' => $penalty->start_at?->toIso8601String(),
            'end_at' => $penalty->end_at?->toIso8601String(),
            'is_active' => $isActive,
            'trip_id' => $penalty->trip_id,
            'trip_code' => $penalty->trip?->trip_code ?? null,
            'report_id' => $penalty->report_id,
            'created_at' => $penalty->created_at?->toIso8601String(),
        ];
    }

    /**
     * Helper to get Vietnamese text for trip status.
     */
    public static function getTripStatusText(int|TripStatus|null $status): string
    {
        $statusVal = $status instanceof TripStatus ? $status->value : (int) ($status ?? 0);
        return match ($statusVal) {
            TripStatus::Pending->value => 'Chờ duyệt',
            TripStatus::WaitingPayment->value => 'Chờ thanh toán',
            TripStatus::Confirmed->value => 'Chưa bắt đầu',
            TripStatus::Ongoing->value => 'Đang diễn ra',
            TripStatus::Complete->value => 'Đã hoàn thành',
            TripStatus::UserCancel->value => 'Đã hủy bởi khách',
            TripStatus::OwnerCancel->value => 'Đã hủy bởi chủ xe',
            TripStatus::WaitingExtension->value => 'Chờ gia hạn',
            TripStatus::WaitingReturn->value => 'Chờ trả xe',
            default => 'Không xác định',
        };
    }
}
