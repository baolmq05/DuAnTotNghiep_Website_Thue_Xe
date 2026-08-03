<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DrivingLicenseSeeder::class,
            UserSeeder::class,
            WalletSeeder::class,
            AddressSeeder::class,
            NotificationSeeder::class,
            CarLocationSeeder::class,
            CarBrandSeeder::class,
            CarTypeSeeder::class,
            CarDeliveryOptionSeeder::class,
            CarUsageLimitSeeder::class,
            CarSeeder::class,
            FavoriteSeeder::class,
            FavoriteItemSeeder::class,
            CarImageSeeder::class,
            TripSeeder::class,
            PromotionSeeder::class,
            PromotionImageSeeder::class,
            PromotionUsageSeeder::class,
            ViewHistorySeeder::class,
            ReviewSeeder::class,
            TransactionSeeder::class,
            FeatureSeeder::class,
            CarFeatureSeeder::class,
            PostCategorySeeder::class,
            PostSeeder::class,
            
        ]);
    }
}
