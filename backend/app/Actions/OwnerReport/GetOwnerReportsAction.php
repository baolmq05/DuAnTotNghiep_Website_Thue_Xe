<?php

namespace App\Actions\OwnerReport;

use App\Models\Report;
use App\Models\User;

class GetOwnerReportsAction
{
    /**
     * Get paginated and filtered list of reports for a car owner.
     */
    public function execute(User $user, array $filters = []): array
    {
        $reportsQuery = Report::whereHas('trip.car', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with([
            'trip.car.carBrand',
            'trip.car.carType',
            'trip.car.images',
            'trip.user',
            'reporter',
            'images',
            'penalty',
        ]);

        // Lọc theo trạng thái report
        if (isset($filters['status']) && in_array((int) $filters['status'], [0, 1, 2])) {
            $reportsQuery->where('status', (int) $filters['status']);
        }

        // Lọc theo loại report
        if (isset($filters['report_type']) && in_array((int) $filters['report_type'], [0, 1, 2, 3])) {
            $reportsQuery->where('report_type', (int) $filters['report_type']);
        }

        // Tìm kiếm theo từ khóa
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $reportsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('trip', function ($tQ) use ($search) {
                        $tQ->where('trip_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('trip.car', function ($cQ) use ($search) {
                        $cQ->where('name', 'like', "%{$search}%")
                            ->orWhere('license_plate', 'like', "%{$search}%");
                    })
                    ->orWhereHas('reporter', function ($rQ) use ($search) {
                        $rQ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo khoảng ngày tạo
        if (!empty($filters['from_date'])) {
            $reportsQuery->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $reportsQuery->whereDate('created_at', '<=', $filters['to_date']);
        }

        // Sắp xếp
        $sortBy = $filters['sort_by'] ?? 'latest';
        if ($sortBy === 'oldest') {
            $reportsQuery->orderBy('created_at', 'asc');
        } else {
            $reportsQuery->orderBy('created_at', 'desc');
        }

        // Phân trang
        $perPage = min(max((int) ($filters['per_page'] ?? 10), 1), 100);
        $paginated = $reportsQuery->paginate($perPage);

        $formattedData = collect($paginated->items())->map(function ($report) {
            return OwnerReportFormatter::formatReportListItem($report);
        });

        return [
            'data' => $formattedData,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ];
    }
}
