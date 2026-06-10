<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarImage;

class CarImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CarImage::create([
            'is_thumbnail' => 1,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSalL5YuETVazW2nQtGuOfXvWdZwxUNHtrO5QMV309kU-4wlE2qeljSaiBS&s=10',
            'car_id' => 1,
        ]);
        CarImage::create([
            'is_thumbnail' => 0,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOm7hAUSCMI5y3YBZ6k2dbbE6A_OOBCB3hZSDk4QgsD2THqK1TeJE-rKuc&s=10',
            'car_id' => 1,  
        ]);
        CarImage::create([
            'is_thumbnail' => 1,
            'image_url' => 'https://img1.oto.com.vn/2024/01/18/toyota-wigo-2023-ts4-7d9b-429a_wm.webp',
            'car_id' => 2,
        ]);
        CarImage::create([
            'is_thumbnail' => 0,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSpBa5ryX_FgtQ0KFl3WFkuf7zSKtoh1z2vFGknXM9K9il-TrenSx25KhNo&s=10',
            'car_id' => 2,
        ]);
        CarImage::create([
            'is_thumbnail' => 0,
            'image_url' => 'https://toyota-saigon.vn/wp-content/uploads/2023/02/corolla-cross-v-2024-8.jpeg',
            'car_id' => 2,
        ]);
        CarImage::create([
            'is_thumbnail' => 1,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0GvX1gH9YJe5FGYAPFOXCx5IYQY-6-DWACKi6qcEAUQ&s=10',
            'car_id' => 3,
        ]);
        CarImage::create([
            'is_thumbnail' => 0,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTy_u2SMGAvRoPLO_gdfnFj7t_W3FZVSC0VTJ36BlFtYw&s=10',
            'car_id' => 3,
        ]);
        CarImage::create([
            'is_thumbnail' => 1,
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREMFM3gqWgMO_ggYgrE0rKAV17mWwdPSJgpNFAEmqbiHEcF0fD_oD_1X4&s=10',
            'car_id' => 4,
        ]);
    }
}
