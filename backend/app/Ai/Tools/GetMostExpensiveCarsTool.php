<?php

namespace App\Ai\Tools;

use App\Models\Car;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Illuminate\Support\Facades\Log;

class GetMostExpensiveCarsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Tìm kiếm danh sách các xe có mức giá thuê cao nhất / đắt nhất / sang trọng / cao cấp / VIP nhất trên nền tảng Drivio. Bắt buộc gọi tool này khi khách hàng hỏi về xe giá cao nhất, xe đắt nhất, xe sang trọng nhất, xe VIP, xe hạng sang. Có thể lọc thêm theo từ khóa hoặc số chỗ ngồi nếu khách có yêu cầu cụ thể.';
    }

    public function handle(Request $request): Stringable|string
    {
        $keyword = trim($request['keyword'] ?? '');
        $seatCount = isset($request['seat_count']) && (int) $request['seat_count'] > 0 ? (int) $request['seat_count'] : null;
        $limit = isset($request['limit']) && (int) $request['limit'] > 0 ? min((int) $request['limit'], 10) : 5;

        Log::info("AI Get Most Expensive Cars - Keyword: '{$keyword}', Seat: '{$seatCount}', Limit: {$limit}");

        $cars = Car::query()
            ->with([
                'carBrand',
                'carType',
                'carLocation',
                'features',
                'owner',
                'images',
            ])
            ->where('status', 1)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('carBrand', function ($sub) use ($keyword) {
                            $sub->where('brand_name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('carType', function ($sub) use ($keyword) {
                            $sub->where('type_name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('features', function ($sub) use ($keyword) {
                            $sub->where('feature_name', 'like', "%{$keyword}%")
                                ->orWhere('description', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when(!is_null($seatCount), function ($query) use ($seatCount) {
                $query->where('seat_count', $seatCount);
            })
            ->orderByRaw('(unit_price - discount_value) DESC')
            ->limit($limit)
            ->get();

        if ($cars->isEmpty()) {
            return json_encode([
                'status' => 'empty',
                'message' => 'Hiện tại không tìm thấy xe cao cấp nào phù hợp với yêu cầu của bạn trên hệ thống Drivio.',
                'cars' => []
            ], JSON_UNESCAPED_UNICODE);
        }

        $carList = $cars->map(function ($car) {
            $actualPrice = $car->discount_value > 0
                ? $car->unit_price - $car->discount_value
                : $car->unit_price;

            $thumbnailImage = $car->images->where('is_thumbnail', 1)->first() ?? $car->images->first();
            $rawImage = $thumbnailImage?->image_url ?? null;
            $thumbnailUrl = null;
            if ($rawImage) {
                if (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://')) {
                    $thumbnailUrl = $rawImage;
                } else {
                    $thumbnailUrl = asset('storage/' . ltrim($rawImage, '/'));
                }
            }

            $featureNames = $car->features->take(3)->pluck('feature_name')->toArray();

            return [
                'id' => $car->id,
                'name' => $car->name,
                'owner' => $car->owner?->name ?? 'Chưa cập nhật',
                'price' => (int) $actualPrice,
                'original_price' => (int) $car->unit_price,
                'discount_value' => (int) $car->discount_value,
                'thumbnail' => $thumbnailUrl,
                'seat_count' => (int) $car->seat_count,
                'transmission' => $car->transmission ?? null,
                'fuel_type' => $car->fuel_type ?? null,
                'location' => $car->carLocation?->name ?? null,
                'features' => $featureNames,
            ];
        })->values()->toArray();

        return json_encode([
            'status' => 'success',
            'message' => "Dưới đây là danh sách " . count($carList) . " xe có giá thuê cao nhất (sang trọng & cao cấp nhất) trên Drivio:",
            'cars' => $carList
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'keyword' => $schema->string()
                ->description('Từ khóa lọc thêm tùy chọn (ví dụ: tên hãng xe "Mercedes", "BMW", loại xe "SUV", "Sedan", tính năng...). Có thể để trống "" nếu muốn tìm xe giá cao nhất toàn hệ thống.')->nullable(),
            'seat_count' => $schema->integer()
                ->description('Số chỗ ngồi cần tìm (ví dụ: 4, 5, 7). Để trống nếu không yêu cầu số chỗ cụ thể.')->nullable(),
            'limit' => $schema->integer()
                ->description('Số lượng xe giá cao nhất cần lấy (mặc định là 5, tối đa là 10).')->nullable(),
        ];
    }
}
