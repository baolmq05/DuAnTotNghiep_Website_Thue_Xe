<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    // Bảng chỉ dùng updated_at, không dùng created_at
    public const CREATED_AT = null;

    protected $fillable = [
        'group',
        'key',
        'value',
        'updated_by',
    ];

    /**
     * Quan hệ tới User (người cập nhật cài đặt gần nhất)
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Lấy giá trị value theo key (Hàm chính theo yêu cầu nhóm).
     * 
     * Ví dụ sử dụng trong dự án:
     *   SystemSetting::get('commission_rate');      // Lấy hoa hồng (ví dụ: '18')
     *   SystemSetting::get('vat_rate');             // Lấy thuế VAT (ví dụ: '7')
     *   SystemSetting::get('hol_amount_rate');      // Lấy tiền phạt nguội (ví dụ: '2')
     *   SystemSetting::get('key_name', 'default');  // Có kèm giá trị mặc định
     *
     * @param string $key Tên key cần lấy value
     * @param mixed $default Giá trị mặc định nếu không tìm thấy trong DB
     * @param string|null $group (Tùy chọn) Nhóm cấu hình
     * @return mixed
     */
    public static function get(string $key, $default = null, ?string $group = null)
    {
        $query = static::where('key', $key);

        if ($group !== null) {
            $query->where('group', $group);
        }

        $setting = $query->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Alias method: Lấy value theo key (Tiện lợi để đọc code dễ hiểu hơn).
     * 
     * Ví dụ: SystemSetting::getValue('commission_rate');
     */
    public static function getValue(string $key, $default = null, ?string $group = null)
    {
        return static::get($key, $default, $group);
    }

    /**
     * Lấy tất cả setting thuộc về một group dưới dạng mảng key => value.
     * 
     * Ví dụ: SystemSetting::getByGroup('finance');
     *
     * @param string $group
     * @return array
     */
    public static function getByGroup(string $group): array
    {
        return static::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Cập nhật hoặc tạo mới một setting.
     * 
     * Ví dụ: SystemSetting::set('commission_rate', '10', 'finance', $userId);
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param int|null $updatedBy
     * @return static
     */
    public static function set(string $key, $value, string $group = 'finance', ?int $updatedBy = null)
    {
        return static::updateOrCreate(
            [
                'group' => $group,
                'key'   => $key,
            ],
            [
                'value'      => (string) $value,
                'updated_by' => $updatedBy,
            ]
        );
    }
}
