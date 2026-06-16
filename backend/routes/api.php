<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PromotionController;


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
});

Route::get('cars', [CarController::class, 'index']);
Route::get('cars/{id}', [CarController::class, 'show']);

Route::group(['middleware' => 'api'], function () {
    Route::get('favorites', [FavoriteController::class, 'index']); 
    Route::post('favorites', [FavoriteController::class, 'store']); 
    Route::delete('favorites/{car_id}', [FavoriteController::class, 'destroy']); 
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
