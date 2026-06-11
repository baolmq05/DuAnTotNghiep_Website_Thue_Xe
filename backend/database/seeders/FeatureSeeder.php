<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Feature::create([
            'feature_name' => 'Bản đồ',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781056273/map-v2_tcaq37.png',
            'description' => 'Tính năng bản đồ giúp người dùng dễ dàng tìm kiếm và định vị các địa điểm, cung cấp thông tin về đường đi, giao thông và các điểm quan tâm khác trên bản đồ.'
        ]);
        Feature::create([
            'feature_name' => 'Bluetooth',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/bluetooth-v2_m9z2wh.png',
            'description' => 'Tính năng Bluetooth cho phép kết nối thiết bị di động với xe, giúp người dùng nghe nhạc, thực hiện cuộc gọi và truy cập các ứng dụng trên điện thoại một cách tiện lợi.'
        ]);
        Feature::create([
            'feature_name' => 'Camera 360',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/360_camera-v2_nbtyux.png',
            'description' => 'Tính năng camera 360 cung cấp góc nhìn toàn diện xung quanh xe, giúp người lái xe có được tầm nhìn rõ ràng hơn và tăng cường an toàn khi lái xe.'
        ]);
        Feature::create([
            'feature_name' => 'Camera cập lề',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/parking_camera-v2_gbpz4n.png',
            'description' => 'Tính năng camera cập lề giúp người lái xe dễ dàng quan sát các vật thể gần bên cạnh xe khi đậu xe hoặc di chuyển trong điều kiện ánh sáng yếu.'
        ]);
        Feature::create([
            'feature_name' => 'Camera hành trình',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/dash_camera-v2_llqrns.png',
            'description' => 'Tính năng camera hành trình ghi lại hình ảnh từ phía trước xe trong quá trình di chuyển, giúp người lái xe có thể xem lại các tình huống xảy ra trên đường.'
        ]);
        Feature::create([
            'feature_name' => 'Camera lùi',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/reverse_camera-v2_yanwh4.png',
            'description' => 'Tính năng camera lùi giúp người lái xe dễ dàng quan sát phía sau xe khi lùi, tăng cường an toàn khi đậu xe hoặc di chuyển trong điều kiện ánh sáng yếu.'
        ]);
        Feature::create([
            'feature_name' => 'Cảm biến lốp',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073783/tpms-v2_kgsvg4.png',
            'description' => 'Tính năng cảm biến lốp theo dõi áp suất lốp và cảnh báo khi áp suất không đạt mức yêu cầu, giúp đảm bảo an toàn và tiết kiệm nhiên liệu.'
        ]);
        Feature::create([
            'feature_name' => 'Cảm biến va chạm',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/impact_sensor-v2_iotcwq.png',
            'description' => 'Tính năng cảm biến va chạm giúp phát hiện va chạm và tự động kích hoạt hệ thống an toàn, giảm thiểu thiệt hại cho người ngồi trong xe.'
        ]);
        Feature::create([
            'feature_name' => 'Cảnh báo tốc độ',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/head_up-v2_fxa7mn.png',
            'description' => 'Tính năng cảnh báo tốc độ giúp người lái xe biết được tốc độ hiện tại và cảnh báo khi vượt quá giới hạn tốc độ cho phép.'
        ]);
        Feature::create([
            'feature_name' => 'Cửa sổ trời',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/sunroof-v2_kyo2mt.png',
            'description' => 'Tính năng cửa sổ trời giúp tăng cường ánh sáng và thông gió cho khoang lái, mang lại cảm giác thoải mái hơn cho người dùng.'
        ]);
        Feature::create([
            'feature_name' => 'Định vị GPS',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/gps-v2_cjy1dg.png',
            'description' => 'Tính năng định vị GPS giúp người lái xe xác định vị trí chính xác và điều hướng dễ dàng.'
        ]);
        Feature::create([
            'feature_name' => 'Ghế trẻ em',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/babyseat-v2_d1gmcr.png',
            'description' => 'Tính năng ghế trẻ em cung cấp sự an toàn và thoải mái cho trẻ em khi di chuyển.'
        ]);
        Feature::create([
            'feature_name' => 'Khe cấm USB',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/usb-v2_vvx1vt.png',
            'description' => 'Tính năng khe cấm USB giúp ngăn chặn việc sử dụng các thiết bị USB không an toàn.'
        ]);
        Feature::create([
            'feature_name' => 'Lốp dự phòng',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/spare_tire-v2_ildbl1.png',
            'description' => 'Tính năng lốp dự phòng giúp người lái xe thay thế lốp bị hỏng một cách nhanh chóng và tiện lợi.'
        ]);
        Feature::create([
            'feature_name' => 'Màn hình DVD',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781074389/dvd-v2_1_uxfubm.png',
            'description' => 'Tính năng màn hình DVD cung cấp trải nghiệm giải trí tuyệt vời cho người dùng.'
        ]);
        Feature::create([
            'feature_name' => 'Nắp thùng xe bán tải',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/bonnet-v2_zirugz.png',
            'description' => 'Tính năng nắp thùng xe bán tải giúp bảo vệ nội thất xe và tăng tính thẩm mỹ.'
        ]);
        Feature::create([
            'feature_name' => 'ETC',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781074484/etc-v2_vepgxt.png',
            'description' => 'Tính năng ETC (Electronic Toll Collection) giúp thanh toán cước phí đường cao tốc một cách nhanh chóng và tiện lợi.'
        ]);
        Feature::create([
            'feature_name' => 'Túi khí',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/airbags-v2_gtkgpl.png',
            'description' => 'Tính năng túi khí giúp bảo vệ người ngồi trong xe trong trường hợp xảy ra tai nạn.'
        ]);
    }
}
