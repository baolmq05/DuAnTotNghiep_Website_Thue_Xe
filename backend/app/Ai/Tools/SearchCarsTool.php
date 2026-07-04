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
            return "
            <div style='padding: 12px; text-align: center; color: #64748b; font-size: 14px;'>
                Không tìm thấy xe nào phù hợp với yêu cầu của bạn.
            </div>
        ";
        }

        $html = "
        <div style='font-family: system-ui, sans-serif; max-width: 100%;'>
            <div style='margin-bottom: 10px; font-size: 14px; color: #334155;'>
                Tìm thấy <strong>{$cars->count()}</strong> xe phù hợp:
            </div>
            <div style='display: flex; flex-direction: column; gap: 8px;'>
        ";

        foreach ($cars as $car) {
            $ownerName = $car->owner?->name ?? 'Chưa cập nhật';
            $price = number_format($car->unit_price, 0, ',', '.');
            $discountPrice = $car->discount_value > 0
                ? number_format($car->unit_price - $car->discount_value, 0, ',', '.')
                : null;

            $url = 'http://localhost:3000/vehicles/' . $car->id;

            $html .= "
                <div style='padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);'>
                    <div style='font-weight: 600; font-size: 15px; color: #1e293b;'>{$car->name}</div>
                    <div style='font-size: 12px; color: #64748b; margin-top: 2px;'>Chủ xe: <strong>{$ownerName}</strong></div>
                    
                    <div style='margin-top: 8px;'>
            ";

            if ($discountPrice) {
                $html .= "
                            <span style='color: #94a3b8; text-decoration: line-through; font-size: 12px; margin-right: 6px;'>{$price}đ</span>
                            <span style='font-weight: 700; color: #e11d48; font-size: 14px;'>{$discountPrice}đ/ngày</span>
                ";
            } else {
                $html .= "
                            <span style='font-weight: 700; color: #1e4e57; font-size: 14px;'>{$price}đ/ngày</span>
                ";
            }

            $html .= "
                    </div>
                    <div style='margin-top: 10px;'>
                        <a href='{$url}' target='_blank' style='font-size: 12px; color: #1e4e57; text-decoration: none; font-weight: 600; hover: text-decoration: underline;'>
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            ";
        }

        $html .= "
            </div>
        </div>
        ";

        return $html;
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
