<?php

namespace App\Ai\Tools;

use App\Models\Car;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

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

        \Illuminate\Support\Facades\Log::info("AI Search Keyword: '{$keyword}', Min Price: '{$minPrice}', Max Price: '{$maxPrice}'");

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
            ])
            ->where('status', 1)
            ->when($keyword !== '', function ($query) use ($keyword) {
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
            return 'Không tìm thấy xe nào phù hợp với yêu cầu của bạn.';
        }

        $output = "Drivio hiện có " . $cars->count() . " xe phù hợp với yêu cầu của bạn:\n\n";

        foreach ($cars as $index => $car) {
            $owner = $car->owner?->name ?? 'Chưa cập nhật';

            $actualPrice = $car->discount_value > 0
                ? $car->unit_price - $car->discount_value
                : $car->unit_price;

            $price = number_format($actualPrice, 0, ',', '.');

            $output .= "**" . ($index + 1) . ". " . $car->name . "**\n";
            $output .= "- **Chủ xe:** " . $owner . "\n";
            $output .= "- **Giá thuê:** " . $price . "đ/ngày\n";

            if ($car->discount_value > 0) {
                $oldPrice = number_format($car->unit_price, 0, ',', '.');
                $output .= "- **Giá gốc:** ~~" . $oldPrice . "đ/ngày~~\n";
            }

            $output .= "- **Chi tiết:** http://localhost:3000/vehicles/{$car->id}\n\n";
        }

        return trim($output);
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
