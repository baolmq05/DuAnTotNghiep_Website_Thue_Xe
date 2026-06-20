<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FacebookAuthController;
use App\Http\Controllers\Api\WalletController;


// API AUTH URL: http://127.0.0.1:8000/api/auth/
// API URL: http://127.0.0.1:8000/api/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::post('profile/driving-license', [AuthController::class, 'submitDrivingLicense']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::post('google', [GoogleAuthController::class, 'loginWithGoogle']);
    Route::post('facebook', [FacebookAuthController::class, 'loginWithFacebook']);
    //api addresses
    Route::get('addresses', [AddressController::class, 'index']);
    Route::post('addresses', [AddressController::class, 'store']);
    Route::put('addresses/{id}', [AddressController::class, 'update']);
    Route::delete('addresses/{id}', [AddressController::class, 'destroy']);

    //api notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications', [NotificationController::class, 'store']);
    Route::put('notifications/read-all', [NotificationController::class, 'readAll']);
    Route::put('notifications/{id}', [NotificationController::class, 'update']);
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);
    Route::get('wallet', [WalletController::class, 'getWalletDetails']);
});

Route::get('cars', [CarController::class, 'index']);
Route::get('cars/{id}', [CarController::class, 'show']);
Route::get('car-brands', [CarController::class, 'getBrands']);
Route::get('car-brands/{id}/types', [CarController::class, 'getTypes']);
Route::get('car-features', [CarController::class, 'getFeatures']);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);
Route::get('post-categories', [PostController::class, 'categories']);

Route::group(['middleware' => 'api'], function () {
    Route::get('favorites', [FavoriteController::class, 'index']); 
    Route::post('favorites', [FavoriteController::class, 'store']); 
    Route::delete('favorites/{car_id}', [FavoriteController::class, 'destroy']); 
    Route::post('cars', [CarController::class, 'store']);
});
// Các route cho khuyến mãi
Route::get('promotions', [PromotionController::class, 'index']);
Route::get('promotions/{id}', [PromotionController::class, 'show']);

// Route cần đăng nhập (admin/user có quyền)
Route::group(['middleware' => 'api'], function () {
    Route::post('promotions', [PromotionController::class, 'store']);
    Route::put('promotions/{id}', [PromotionController::class, 'update']);
    Route::delete('promotions/{id}', [PromotionController::class, 'destroy']);
});
