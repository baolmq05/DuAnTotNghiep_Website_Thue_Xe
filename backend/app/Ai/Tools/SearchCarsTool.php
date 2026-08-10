<?php

namespace App\Ai\Tools;

use App\Models\Car;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Illuminate\Support\Facades\Log;

class SearchCarsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Tìm kiếm xe cho thuê dựa theo từ khóa và mức giá. BẮT BUỘC sử dụng chính xác từ khóa nhu cầu thực tế của người dùng (ví dụ: "leo núi", "dã ngoại", "đi phượt", "tiết kiệm xăng") để truyền vào làm `keyword`. Có thể lọc thêm theo giá tối đa (max_price) và giá tối thiểu (min_price) nếu người dùng có yêu cầu về giá.';
    }

    public function handle(Request $request): Stringable|string
    {
        $keyword = trim($request['keyword'] ?? '');
        $maxPrice = $request['max_price'] ?? null;
        $minPrice = $request['min_price'] ?? null;

        Log::info("AI Search Keyword: '{$keyword}', Min Price: '{$minPrice}', Max Price: '{$maxPrice}'");

        if ($keyword === '' && is_null($maxPrice) && is_null($minPrice)) {
            return '<p>Vui lòng nhập thông tin tìm kiếm xe.</p>';
        }

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
            ->when($keyword != '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('carType', function ($sub) use ($keyword) {
                            $sub->where('type_name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('features', function ($sub) use ($keyword) {
                            $sub->where('feature_name', 'like', "%{$keyword}%")
                                ->orWhere('description', 'like', "%{$keyword}%");
                        });
                });
            })
            // Lọc theo giá sau khi đã trừ khuyến mãi (giá thuê thực tế)
            ->when(!is_null($maxPrice), function ($query) use ($maxPrice) {
                $query->whereRaw('(unit_price - discount_value) <= ?', [(int) $maxPrice]);
            })
            ->when(!is_null($minPrice), function ($query) use ($minPrice) {
                $query->whereRaw('(unit_price - discount_value) >= ?', [(int) $minPrice]);
            })
            ->limit(5)
            ->get();

        if ($cars->isEmpty()) {
            return json_encode([
                'status' => 'empty',
                'message' => 'Không tìm thấy xe nào phù hợp với yêu cầu của bạn.',
                'cars' => []
            ], JSON_UNESCAPED_UNICODE);
        }

        $carList = $cars->map(function ($car) {
            $actualPrice = $car->discount_value > 0
                ? $car->unit_price - $car->discount_value
                : $car->unit_price;

            // Lấy ảnh thumbnail từ bảng car_images (ưu tiên is_thumbnail = 1)
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

            // Lấy tối đa 3 tính năng nổi bật từ bảng car_features
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
            'message' => "Drivio hiện có " . count($carList) . " xe phù hợp với yêu cầu của bạn:",
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
                ->required()
                ->description('Từ khóa tìm kiếm xe (như tên xe, loại xe, tính năng, hoặc nhu cầu như leo núi, dã ngoại...). Nếu chỉ tìm theo giá mà không có từ khóa cụ thể, truyền chuỗi rỗng "".'),
            'max_price' => $schema->integer()
                ->description('Mức giá thuê tối đa mỗi ngày (đơn vị: VNĐ/ngày). Ví dụ nếu tìm xe dưới 500k thì truyền vào 500000.')->nullable(),
            'min_price' => $schema->integer()
                ->description('Mức giá thuê tối thiểu mỗi ngày (đơn vị: VNĐ/ngày). Ví dụ nếu tìm xe trên 300k thì truyền vào 300000.')->nullable(),
        ];
    }
}
