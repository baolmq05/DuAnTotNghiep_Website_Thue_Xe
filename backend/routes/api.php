<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FacebookAuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CarCalendarController;
use App\Http\Controllers\Api\MyTripController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\VNPayController;
use App\Http\Controllers\Api\ZaloPayController;
use App\Http\Controllers\Api\DashboardController;

Route::get('cars', [CarController::class, 'index']);
Route::get('cars/{id}', [CarController::class, 'show']);
Route::get('car-brands', [CarController::class, 'getBrands']);
Route::get('car-brands/{id}/types', [CarController::class, 'getTypes']);
Route::get('car-features', [CarController::class, 'getFeatures']);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);
Route::get('post-categories', [PostController::class, 'categories']);

Route::get('promotions', [PromotionController::class, 'index']);
Route::get('promotions/{id}', [PromotionController::class, 'show']);

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
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::post('google', [GoogleAuthController::class, 'loginWithGoogle']);
    Route::post('facebook', [FacebookAuthController::class, 'loginWithFacebook']);

    Route::get('addresses', [AddressController::class, 'index']);
    Route::post('addresses', [AddressController::class, 'store']);
    Route::put('addresses/{id}', [AddressController::class, 'update']);
    Route::delete('addresses/{id}', [AddressController::class, 'destroy']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications', [NotificationController::class, 'store']);
    Route::put('notifications/read-all', [NotificationController::class, 'readAll']);
    Route::put('notifications/{id}', [NotificationController::class, 'update']);
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);

    Route::get('wallet', [WalletController::class, 'getWalletDetails']);

    // VNPay Authenticated routes
    Route::post('vnpay/create-payment', [VNPayController::class, 'createPayment']);
    // ZaloPay Authenticated routes
    Route::post('zalopay/create-payment', [ZaloPayController::class, 'createPayment']);
    Route::get('chatbot', [AgentController::class, 'index']);
    Route::post('chatbot', [AgentController::class, 'store']);
});

Route::group(['middleware' => 'api'], function () {
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites', [FavoriteController::class, 'store']);
    Route::delete('favorites/{car_id}', [FavoriteController::class, 'destroy']);

    Route::post('cars', [CarController::class, 'store']);
    Route::put('cars/{id}', [CarController::class, 'update']);

    Route::get('trips', [TripController::class, 'index']);
    Route::post('trips', [TripController::class, 'store']);
    Route::get('trips/{id}', [TripController::class, 'show']);
    Route::post('trips/{id}/start', [TripController::class, 'startTrip']);
    Route::put('trips/{id}/confirm', [TripController::class, 'confirm']);
    Route::put('trips/{id}/reject', [TripController::class, 'reject']);
    Route::post('trips/{id}/extension-request', [TripController::class, 'requestExtension']);
    Route::put('trips/{id}/extension-approve', [TripController::class, 'approveExtension']);
    Route::put('trips/{id}/extension-reject', [TripController::class, 'rejectExtension']);
    Route::get('my-trips', [MyTripController::class, 'index']);

    Route::post('promotions', [PromotionController::class, 'store']);
    Route::put('promotions/{id}', [PromotionController::class, 'update']);
    Route::delete('promotions/{id}', [PromotionController::class, 'destroy']);

    Route::get('car-calendar', [CarCalendarController::class, 'index']);
    Route::get('dashboard', [DashboardController::class, 'index']);


    //Chat
    Route::get('conversations', [ChatController::class, 'index']);
    Route::post('conversations', [ChatController::class, 'storeConversation']);
    Route::get('messages/{id}', [ChatController::class, 'getMessages']);
    Route::post('messages', [ChatController::class, 'storeMessage']);
    Route::put('conversations/{id}/read', [ChatController::class, 'markAsRead']);

    // Register Broadcasting Auth with JWT Auth middleware
    Broadcast::routes(['middleware' => ['api', 'auth:api']]);
});

// VNPay Public Callback/Verification routes
Route::get('vnpay/ipn', [VNPayController::class, 'ipn']);
Route::get('vnpay/verify', [VNPayController::class, 'verify']);

// ZaloPay Public Callback/Verification routes
Route::post('zalopay/callback', [ZaloPayController::class, 'callback']);
Route::get('zalopay/verify', [ZaloPayController::class, 'verify']);
Route::get('zalopay/banks', [ZaloPayController::class, 'getBanks']);
