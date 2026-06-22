<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use Carbon\Carbon;

class CarCalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem lịch trình xe.'
            ], 401);
        }

        //  KIỂM TRA XEM NGƯỜI DÙNG CÓ ĐANG LỌC NGÀY HAY KHÔNG
        $isFilteringDate = $request->filled('fromDate') && $request->filled('toDate');

        $now = null;
        $toDate = null;

        if ($isFilteringDate) {
            $now = Carbon::parse($request->fromDate)->startOfDay();
            $toDate = Carbon::parse($request->toDate)->endOfDay();
        } else {
            // Nếu clear trống ngày -> Lấy mốc tuần hiện tại làm chuẩn để vẽ 7 ô Timeline
            $currentMonday = Carbon::now()->startOfWeek()->startOfDay();
            if ($request->filled('fromDate')) {
                $currentMonday = Carbon::parse($request->fromDate)->startOfWeek()->startOfDay();
            }
            $now = $currentMonday;
            $toDate = $currentMonday->copy()->endOfWeek()->endOfDay();
        }

        // KHỞI TẠO QUERY CƠ BẢN
        $query = Car::where('user_id', $user->id)
            ->where('status', 1)
            ->with(['carBrand', 'carType', 'carLocation', 'images', 'trips']);

        // Tìm kiếm nhanh theo tên hoặc biển số
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }

        // Lọc theo Hãng xe
        if ($request->filled('brandFilter') && $request->brandFilter !== 'all') {
            $query->whereHas('carBrand', function ($q) use ($request) {
                $q->where('brand_name', 'like', "%{$request->brandFilter}%");
            });
        }

        // Lọc theo Dòng xe
        if ($request->filled('typeFilter') && $request->typeFilter !== 'all') {
            $query->whereHas('carType', function ($q) use ($request) {
                $q->where('type_name', 'like', "%{$request->typeFilter}%");
            });
        }

        // BỘ LỌC CÂU LỆNH SQL: Chỉ lọc giao thoa cứng nếu Front-end có truyền chọn ngày
        if ($isFilteringDate) {
            $query->whereHas('trips', function ($q) use ($now, $toDate) {
                $q->where('start_at', '<=', $toDate)
                    ->where('end_at', '>=', $now)
                    ->whereIn('status', [0, 1, 2, 3, 4]);
            });
        }

        $cars = $query->get();

        // TÁI CẤU TRÚC: Duyệt tách riêng từng Chuyến đi thành các Card độc lập
        $formattedData = collect();

        foreach ($cars as $car) {
            $tripsInPeriod = $car->trips->filter(function ($trip) use ($isFilteringDate, $now, $toDate) {
                if (!$isFilteringDate) {
                    return in_array($trip->status, [0, 1, 2, 3, 4]);
                }
                $tripStart = Carbon::parse($trip->start_at)->startOfDay();
                $tripEnd = Carbon::parse($trip->end_at)->endOfDay();
                return $tripStart <= $toDate && $tripEnd >= $now && in_array($trip->status, [0, 1, 2, 3, 4]);
            })->sortBy('start_at');

            if ($tripsInPeriod->isNotEmpty()) {
                foreach ($tripsInPeriod as $trip) {
                    $note = 'Sẵn sàng đón khách';
                    if ($trip->status == 1) $note = "Đang Có chuyến đi diễn ra";
                    else if ($trip->status == 0) $note = "Lịch hẹn tiếp theo (Chờ giao)";
                    else if ($trip->status == 2) $note = "Hoàn thành chuyến đi";
                    else if ($trip->status == 3) $note = "Chuyến đi bị người dùng hủy";
                    else if ($trip->status == 4) $note = "Chuyến đi bị chủ xe hủy";

                    $thumbnailImage = $car->images->where('is_thumbnail', 1)->first() ?? $car->images->first();

                    $formattedData->push([
                        'trip_id' => $trip->id,
                        'name' => $car->name,
                        'brand' => $car->carBrand->brand_name ?? 'Không rõ',
                        'type' => $car->carType->type_name ?? 'Không rõ',
                        'licensePlate' => $car->license_plate,
                        'image' => $thumbnailImage->image_url ?? null,
                        'location' => $car->carLocation->street_name ?? 'Không rõ',
                        'manufactureYear' => Carbon::parse($car->manufacture_year)->format('Y'),
                        'status' => $trip->status == 1 ? 1 : ($trip->status == 0 ? 2 : 0),
                        'trip_status' => $trip->status,
                        'bookedDates' => Carbon::parse($trip->start_at)->format('d/m') . ' - ' . Carbon::parse($trip->end_at)->format('d/m/Y'),
                        'note' => $note,
                        'weekTimeline' => $this->calculateRealTimeline($car, $now),
                        'modalTimeline' => $this->calculateTripDetailedTimeline($trip)
                    ]);
                }
            } else {
                // Giữ lại 1 Card thông báo xe trống nếu không dính lịch nào trong tuần
                $thumbnailImage = $car->images->where('is_thumbnail', 1)->first() ?? $car->images->first();
                $formattedData->push([
                    'trip_id' => 'empty-' . $car->id,
                    'name' => $car->name,
                    'brand' => $car->carBrand->brand_name ?? 'Không rõ',
                    'type' => $car->carType->type_name ?? 'Không rõ',
                    'licensePlate' => $car->license_plate,
                    'image' => $thumbnailImage->image_url ?? null,
                    'location' => $car->carLocation->street_name ?? 'Không rõ',
                    'manufactureYear' => Carbon::parse($car->manufacture_year)->format('Y'),
                    'status' => 0,
                    'trip_status' => null,
                    'bookedDates' => null,
                    'note' => 'Sẵn sàng đón khách',
                    'weekTimeline' => $this->calculateRealTimeline($car, $now)
                ]);
            }
        }

        // Lọc theo trạng thái (statusFilter) sau khi map dữ liệu
        if ($request->filled('statusFilter') && $request->statusFilter !== 'all') {
            $statusFilter = (int)$request->statusFilter;
            $formattedData = $formattedData->filter(function ($item) use ($statusFilter) {
                return $item['status'] === $statusFilter;
            })->values();
        }

        // Xử lý sắp xếp sortBy
        if ($request->filled('sortBy')) {
            $sortBy = $request->sortBy;
            if ($sortBy === 'default') {
                $formattedData = $formattedData->sortBy('status');
            } elseif ($sortBy === 'brand') {
                $formattedData = $formattedData->sortBy('brand', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($sortBy === 'type') {
                $formattedData = $formattedData->sortBy('type', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($sortBy === 'name') {
                $formattedData = $formattedData->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($sortBy === 'busy') {
                $formattedData = $formattedData->sortByDesc('status');
            }
        }

        // BỔ SUNG ĐÚNG VỊ TRÍ: Trả kết quả JSON về cho Nuxt 3 gặm nhấm hiển thị
        return response()->json($formattedData->values()->all(), 200);
    }

    private function calculateRealTimeline($car, $now)
    {
        $daysOfWeek = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
        $timeline = [];
        $startOfWeek = $now->copy()->startOfWeek();

        foreach ($daysOfWeek as $index => $day) {
            $currentDay = $startOfWeek->copy()->addDays($index);

            $tripOnDay = $car->trips->first(function ($trip) use ($currentDay) {
                return $currentDay->between(
                    Carbon::parse($trip->start_at)->startOfDay(),
                    Carbon::parse($trip->end_at)->endOfDay()
                ) && in_array($trip->status, [0, 1, 2, 3, 4]);
            });

            if ($tripOnDay) {
                if ($tripOnDay->status == 1) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-emerald-500', 'tooltip' => 'Đang diễn ra'];
                } else if ($tripOnDay->status == 0) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-amber-500', 'tooltip' => 'Chưa bắt đầu (Chờ giao)'];
                } else if ($tripOnDay->status == 2) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-indigo-500', 'tooltip' => 'Đã hoàn thành'];
                } else if ($tripOnDay->status == 3) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-purple-500', 'tooltip' => 'Đã hủy bởi người dùng'];
                } else if ($tripOnDay->status == 4) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-rose-500', 'tooltip' => 'Đã hủy bởi chủ xe'];
                }
            } else {
                $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-gray-200 text-slate-600', 'tooltip' => 'Trống lịch'];
            }
        }

        return $timeline;
    }

    // Tính toán timeline chi tiết cho từng chuyến đi trong modal
    private function calculateTripDetailedTimeline($trip)
    {
        $startDate = Carbon::parse($trip->start_at)->startOfDay();
        $endDate = Carbon::parse($trip->end_at)->endOfDay();
        
        // Mảng ánh xạ Thứ sang tiếng Việt viết tắt
        $dayNames = [
            1 => 'T2',
            2 => 'T3',
            3 => 'T4',
            4 => 'T5',
            5 => 'T6',
            6 => 'T7',
            7 => 'CN'
        ];

        $timeline = [];
        
        // Chạy vòng lặp từ ngày bắt đầu đến ngày kết thúc của chuyến đi
        $currentDay = $startDate->copy();
        while ($currentDay->lte($endDate)) {
            
            $dayOfWeekIso = $currentDay->dayOfWeekIso;
            $label = $dayNames[$dayOfWeekIso] ?? 'N/A';

            // Tô màu theo trạng thái của chính chuyến đi này
            $color = 'bg-gray-200';
            $tooltip = 'Trống lịch';

            if ($trip->status == 1) {
                $color = 'bg-emerald-500';
                $tooltip = 'Đang diễn ra';
            } else if ($trip->status == 0) {
                $color = 'bg-amber-500';
                $tooltip = 'Chưa bắt đầu (Chờ giao)';
            } else if ($trip->status == 2) {
                $color = 'bg-indigo-500';
                $tooltip = 'Đã hoàn thành';
            } else if ($trip->status == 3) {
                $color = 'bg-purple-500';
                $tooltip = 'Đã hủy bởi người dùng';
            } else if ($trip->status == 4) {
                $color = 'bg-rose-500';
                $tooltip = 'Đã hủy bởi chủ xe';
            }

            $timeline[] = [
                'label' => $label,
                'date_num' => $currentDay->day,
                'full_date' => $currentDay->format('d/m/Y'),
                'color' => $color,
                'tooltip' => $tooltip
            ];

            // Tịnh tiến thêm 1 ngày
            $currentDay->addDay();
        }

        return $timeline;
    }
}
