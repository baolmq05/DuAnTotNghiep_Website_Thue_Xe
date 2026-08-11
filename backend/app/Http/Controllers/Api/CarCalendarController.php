<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use Carbon\Carbon;

class CarCalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('api')->user();

        // CHECK WHETHER THE USER IS FILTERING BY DATE
        $isFilteringDate = $request->filled('fromDate') && $request->filled('toDate');

        $now = null;
        $toDate = null;

        if ($isFilteringDate) {
            $now = Carbon::parse($request->fromDate)->startOfDay();
            $toDate = Carbon::parse($request->toDate)->endOfDay();
        } else {
            // If the date is cleared, use the current week as the reference point to draw a 7-cell timeline
            $currentMonday = Carbon::now()->startOfWeek()->startOfDay();
            if ($request->filled('fromDate')) {
                $currentMonday = Carbon::parse($request->fromDate)->startOfWeek()->startOfDay();
            }
            $now = $currentMonday;
            $toDate = $currentMonday->copy()->endOfWeek()->endOfDay();
        }

        // Create query
        $query = Car::where('user_id', $user->id)
            ->where('status', 1)
            ->with(['carBrand', 'carType', 'carLocation', 'images', 'trips']);

        // Filter by car name and license plate
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }

        // Filter by car brand
        if ($request->filled('brandFilter') && $request->brandFilter !== 'all') {
            $query->whereHas('carBrand', function ($q) use ($request) {
                $q->where('brand_name', 'like', "%{$request->brandFilter}%");
            });
        }

        // Filter by car type
        if ($request->filled('typeFilter') && $request->typeFilter !== 'all') {
            $query->whereHas('carType', function ($q) use ($request) {
                $q->where('type_name', 'like', "%{$request->typeFilter}%");
            });
        }

        // If there is a date filter
        if ($isFilteringDate) {
            $query->whereHas('trips', function ($q) use ($now, $toDate) {
                $q->where('start_at', '<=', $toDate)
                    ->where('end_at', '>=', $now)
                    ->whereIn('status', [
                        TripStatus::Pending->value, // 0 - pending approval
                        TripStatus::WaitingPayment->value,  // 1 - waiting payment
                        TripStatus::Confirmed->value,       // 2 - confirmed
                        TripStatus::Ongoing->value,         // 3 - in progress
                        TripStatus::Complete->value,        // 4 - completed
                        TripStatus::UserCancel->value,      // 5 - user canceled
                        TripStatus::OwnerCancel->value,     // 6 - car owner canceled
                        TripStatus::WaitingExtension->value, // 7 - waiting for extension
                        TripStatus::WaitingReturn->value,    // 8 - waiting for return
                    ]);
            });
        }

        $cars = $query->get();

        // RESTRUCTURE: Process each trip separately and create individual cards
        $formattedData = collect();

        foreach ($cars as $car) {
            $tripsInPeriod = $car->trips->filter(function ($trip) use ($isFilteringDate, $now, $toDate) {
                if (!$isFilteringDate) {
                    return in_array($trip->status, [
                        TripStatus::Pending->value,
                        TripStatus::WaitingPayment->value,
                        TripStatus::Confirmed->value,
                        TripStatus::Ongoing->value,
                        TripStatus::Complete->value,
                        TripStatus::UserCancel->value,
                        TripStatus::OwnerCancel->value,
                        TripStatus::WaitingExtension->value,
                        TripStatus::WaitingReturn->value,
                    ]);
                }
                $tripStart = Carbon::parse($trip->start_at)->startOfDay();
                $tripEnd = Carbon::parse($trip->end_at)->endOfDay();
                return $tripStart <= $toDate && $tripEnd >= $now && in_array($trip->status, [
                    TripStatus::Pending->value,
                    TripStatus::WaitingPayment->value,
                    TripStatus::Confirmed->value,
                    TripStatus::Ongoing->value,
                    TripStatus::Complete->value,
                    TripStatus::UserCancel->value,
                    TripStatus::OwnerCancel->value,
                    TripStatus::WaitingExtension->value,
                    TripStatus::WaitingReturn->value,
                ]);
            })->sortBy('start_at');

            if ($tripsInPeriod->isNotEmpty()) {
                foreach ($tripsInPeriod as $trip) {
                    $note = 'Sẵn sàng đón khách';
                    if ($trip->status == TripStatus::Pending->value) $note = "Chuyến đi đang chờ duyệt";
                    else if ($trip->status == TripStatus::WaitingPayment->value) $note = "Chuyến đi chờ thanh toán";
                    else if ($trip->status == TripStatus::Confirmed->value) $note = "Lịch hẹn tiếp theo (Chờ giao)";
                    else if ($trip->status == TripStatus::Ongoing->value) $note = "Đang có chuyến đi diễn ra";
                    else if ($trip->status == TripStatus::WaitingExtension->value) $note = "Chuyến đi chờ gia hạn";
                    else if ($trip->status == TripStatus::WaitingReturn->value) $note = "Chuyến đi chờ trả xe";
                    else if ($trip->status == TripStatus::Complete->value) $note = "Hoàn thành chuyến đi";
                    else if ($trip->status == TripStatus::UserCancel->value) $note = "Chuyến đi bị người dùng hủy";
                    else if ($trip->status == TripStatus::OwnerCancel->value) $note = "Chuyến đi bị chủ xe hủy";

                    $thumbnailImage = $car->images->where('is_thumbnail', 1)->first() ?? $car->images->first();

                    $formattedData->push([
                        'trip_id' => $trip->id,
                        'name' => $car->name,
                        'brand' => $car->carBrand->brand_name ?? 'Không rõ',
                        'type' => $car->carType->type_name ?? 'Không rõ',
                        'licensePlate' => $car->license_plate,
                        'image' => $thumbnailImage->image_url ?? null,
                        'location' => $car->carLocation->address ?? 'Không rõ',
                        'manufactureYear' => Carbon::parse($car->manufacture_year)->format('Y'),
                        'status' => $trip->status,
                        'trip_status' => $trip->status,
                        'bookedDates' => Carbon::parse($trip->start_at)->format('d/m') . ' - ' . Carbon::parse($trip->end_at)->format('d/m/Y'),
                        'note' => $note,
                        'weekTimeline' => $this->calculateRealTimeline($car, $now),
                        'modalTimeline' => $this->calculateTripDetailedTimeline($trip)
                    ]);
                }
            } else {
                // Keep one card to show the car is available if there is no booking in the week
                $thumbnailImage = $car->images->where('is_thumbnail', 1)->first() ?? $car->images->first();
                $formattedData->push([
                    'trip_id' => 'empty-' . $car->id,
                    'name' => $car->name,
                    'brand' => $car->carBrand->brand_name ?? 'Không rõ',
                    'type' => $car->carType->type_name ?? 'Không rõ',
                    'licensePlate' => $car->license_plate,
                    'image' => $thumbnailImage->image_url ?? null,
                    'location' => $car->carLocation->address ?? 'Không rõ',
                    'manufactureYear' => Carbon::parse($car->manufacture_year)->format('Y'),
                    'status' => null,
                    'trip_status' => null,
                    'bookedDates' => null,
                    'note' => 'Sẵn sàng đón khách',
                    'weekTimeline' => $this->calculateRealTimeline($car, $now)
                ]);
            }
        }

        // Filter by status after mapping the data
        if ($request->filled('statusFilter') && $request->statusFilter !== 'all') {
            $statusFilter = (int)$request->statusFilter;
            $formattedData = $formattedData->filter(function ($item) use ($statusFilter) {
                return $item['status'] == $statusFilter;
            })->values();
        }

        // Handle sorting by sortBy
        if ($request->filled('sortBy')) {
            $sortBy = $request->sortBy;
            if ($sortBy == 'default') {
                $formattedData = $formattedData->sortBy('status');
            } elseif ($sortBy == 'brand') {
                $formattedData = $formattedData->sortBy('brand', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($sortBy == 'type') {
                $formattedData = $formattedData->sortBy('type', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($sortBy == 'name') {
                $formattedData = $formattedData->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($sortBy == 'busy') {
                $formattedData = $formattedData->sortByDesc('status');
            }
        }

        // IMPORTANT: Return the JSON result in the correct format for Nuxt 3 to display
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
                ) && in_array($trip->status, [
                    TripStatus::Pending->value,
                    TripStatus::WaitingPayment->value,
                    TripStatus::Confirmed->value,
                    TripStatus::Ongoing->value,
                    TripStatus::Complete->value,
                    TripStatus::UserCancel->value,
                    TripStatus::OwnerCancel->value,
                    TripStatus::WaitingExtension->value,
                    TripStatus::WaitingReturn->value,
                ]);
            });

            if ($tripOnDay) {
                if ($tripOnDay->status == TripStatus::Pending->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-amber-400', 'tooltip' => 'Chờ duyệt'];
                } else if ($tripOnDay->status == TripStatus::WaitingPayment->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-orange-400', 'tooltip' => 'Chờ thanh toán'];
                } else if ($tripOnDay->status == TripStatus::Confirmed->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-amber-500', 'tooltip' => 'Chưa bắt đầu (Chờ giao)'];
                } else if ($tripOnDay->status == TripStatus::Ongoing->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-emerald-500', 'tooltip' => 'Đang diễn ra'];
                } else if ($tripOnDay->status == TripStatus::WaitingExtension->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-indigo-400', 'tooltip' => 'Chờ gia hạn'];
                } else if ($tripOnDay->status == TripStatus::WaitingReturn->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-sky-400', 'tooltip' => 'Chờ trả xe'];
                } else if ($tripOnDay->status == TripStatus::Complete->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-indigo-500', 'tooltip' => 'Đã hoàn thành'];
                } else if ($tripOnDay->status == TripStatus::UserCancel->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-purple-500', 'tooltip' => 'Đã hủy bởi người dùng'];
                } else if ($tripOnDay->status == TripStatus::OwnerCancel->value) {
                    $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-rose-500', 'tooltip' => 'Đã hủy bởi chủ xe'];
                }
            } else {
                $timeline[] = ['label' => $day, 'date_num' => $currentDay->day, 'color' => 'bg-gray-200 text-slate-600', 'tooltip' => 'Trống lịch'];
            }
        }

        return $timeline;
    }

    // Calculate the detailed timeline for each trip in the modal
    private function calculateTripDetailedTimeline($trip)
    {
        $startDate = Carbon::parse($trip->start_at)->startOfDay();
        $endDate = Carbon::parse($trip->end_at)->endOfDay();

        // Array mapping weekdays to short Vietnamese names
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

        // Loop from the trip start date to the trip end date
        $currentDay = $startDate->copy();
        while ($currentDay->lte($endDate)) {

            $dayOfWeekIso = $currentDay->dayOfWeekIso;
            $label = $dayNames[$dayOfWeekIso] ?? 'N/A';

            // Color based on this trip's status
            $color = 'bg-gray-200';
            $tooltip = 'Trống lịch';

            if ($trip->status == TripStatus::Pending->value) {
                $color = 'bg-amber-400';
                $tooltip = 'Chờ duyệt';
            } else if ($trip->status == TripStatus::WaitingPayment->value) {
                $color = 'bg-orange-400';
                $tooltip = 'Chờ thanh toán';
            } else if ($trip->status == TripStatus::Confirmed->value) {
                $color = 'bg-amber-500';
                $tooltip = 'Chưa bắt đầu (Chờ giao)';
            } else if ($trip->status == TripStatus::Ongoing->value) {
                $color = 'bg-emerald-500';
                $tooltip = 'Đang diễn ra';
            } else if ($trip->status == TripStatus::WaitingExtension->value) {
                $color = 'bg-indigo-400';
                $tooltip = 'Chờ gia hạn';
            } else if ($trip->status == TripStatus::WaitingReturn->value) {
                $color = 'bg-sky-400';
                $tooltip = 'Chờ trả xe';
            } else if ($trip->status == TripStatus::Complete->value) {
                $color = 'bg-indigo-500';
                $tooltip = 'Đã hoàn thành';
            } else if ($trip->status == TripStatus::UserCancel->value) {
                $color = 'bg-purple-500';
                $tooltip = 'Đã hủy bởi người dùng';
            } else if ($trip->status == TripStatus::OwnerCancel->value) {
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

            // Move forward by one day
            $currentDay->addDay();
        }

        return $timeline;
    }
}
