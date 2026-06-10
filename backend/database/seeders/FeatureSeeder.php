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
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781056273/map-v2_tcaq37.png'
        ]);
        Feature::create([
            'feature_name' => 'Bluetooth',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/bluetooth-v2_m9z2wh.png'
        ]);
        Feature::create([
            'feature_name' => 'Camera 360',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/360_camera-v2_nbtyux.png'
        ]);
        Feature::create([
            'feature_name' => 'Camera cập lề',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/parking_camera-v2_gbpz4n.png'
        ]);
        Feature::create([
            'feature_name' => 'Camera hành trình',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/dash_camera-v2_llqrns.png'
        ]);
        Feature::create([
            'feature_name' => 'Camera lùi',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/reverse_camera-v2_yanwh4.png'
        ]);
        Feature::create([
            'feature_name' => 'Cảm biến lốp',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073783/tpms-v2_kgsvg4.png'
        ]);
        Feature::create([
            'feature_name' => 'Cảm biến va chạm',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/impact_sensor-v2_iotcwq.png'
        ]);
        Feature::create([
            'feature_name' => 'Cảnh báo tốc độ',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/head_up-v2_fxa7mn.png'
        ]);
        Feature::create([
            'feature_name' => 'Cửa sổ trời',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/sunroof-v2_kyo2mt.png'
        ]);
        Feature::create([
            'feature_name' => 'Định vị GPS',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/gps-v2_cjy1dg.png'
        ]);
        Feature::create([
            'feature_name' => 'Ghế trẻ em',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/babyseat-v2_d1gmcr.png'
        ]);
        Feature::create([
            'feature_name' => 'Khe cấm USB',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/usb-v2_vvx1vt.png'
        ]);
        Feature::create([
            'feature_name' => 'Lốp dự phòng',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/spare_tire-v2_ildbl1.png'
        ]);
        Feature::create([
            'feature_name' => 'Màn hình DVD',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781074389/dvd-v2_1_uxfubm.png'
        ]);
        Feature::create([
            'feature_name' => 'Nắp thùng xe bán tải',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/bonnet-v2_zirugz.png'
        ]);
        Feature::create([
            'feature_name' => 'ETC',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781074484/etc-v2_vepgxt.png'
        ]);
        Feature::create([
            'feature_name' => 'Túi khí',
            'icon' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/airbags-v2_gtkgpl.png'
        ]);
    }
}
