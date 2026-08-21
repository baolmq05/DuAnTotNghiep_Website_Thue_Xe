<?php

namespace App\Actions\OwnerReport;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetOwnerReportDetailAction
{
    /**
     * Get details of a single report for a car owner.
     *
     * @throws ModelNotFoundException
     * @throws AccessDeniedHttpException
     */
    public function execute(User $user, int|string $id): array
    {
        $report = Report::with([
            'trip.car.carBrand',
            'trip.car.carType',
            'trip.car.images',
            'trip.user',
            'reporter',
            'resolver',
            'images',
            'penalty.resolver',
        ])->find($id);

        if (!$report) {
            throw new ModelNotFoundException('Báo cáo không tồn tại.');
        }

        // Kiểm tra quyền sở hữu: Xe thuộc về chủ xe đang đăng nhập
        $ownerId = $report->trip?->car?->user_id;
        if ($ownerId !== $user->id) {
            throw new AccessDeniedHttpException('Bạn không có quyền xem báo cáo này.');
        }

        return OwnerReportFormatter::formatReportDetail($report);
    }
}
