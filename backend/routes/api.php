<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AddressController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CarCalendarController;
use App\Http\Controllers\Api\MyTripController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\VNPayController;
use App\Http\Controllers\Api\ZaloPayController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SeePayController;
use App\Http\Controllers\Api\ExtensionTripController;
use App\Http\Controllers\Api\DrivingLicenseController;
use App\Http\Controllers\Api\ViewHistoryController;
use App\Http\Controllers\Api\ReportController;

// ===================================================================
// PUBLIC ROUTES - No authentication required
// ===================================================================

// Cars (public listing & detail)
Route::get('cars', [CarController::class, 'index']);
Route::get('cars/{id}', [CarController::class, 'show']);
Route::get('car-brands', [CarController::class, 'getBrands']);
Route::get('car-brands/{id}/types', [CarController::class, 'getTypes']);
Route::get('car-features', [CarController::class, 'getFeatures']);

// Posts
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);
Route::get('post-categories', [PostController::class, 'categories']);

// Promotions (public listing & detail)
Route::get('promotions', [PromotionController::class, 'index']);
Route::get('promotions/{id}', [PromotionController::class, 'show']);

// Public profiles & reviews
Route::get('reviews/{targetId}', [ReviewController::class, 'getProfileReviews']);
Route::get('owner/profile/{id}', [CarController::class, 'getOwnerProfileInfo']);

// Auth (login, register, password reset - no login required)
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('google', [GoogleAuthController::class, 'loginWithGoogle']);
});

// Payment webhooks & callbacks (public endpoints called by payment providers)
Route::get('vnpay/ipn', [VNPayController::class, 'ipn']);
Route::post('zalopay/callback', [ZaloPayController::class, 'callback']);
Route::get('zalopay/banks', [ZaloPayController::class, 'getBanks']);
Route::post('sepay/webhook', [SeePayController::class, 'handleWebhook']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('vnpay/verify', [VNPayController::class, 'verify']);
    Route::get('zalopay/verify', [ZaloPayController::class, 'verify']);
});

// ===================================================================
// AUTHENTICATED ROUTES - Require valid JWT token (auth:api)
// ===================================================================

// User account routes (prefix: /api/auth/...)
Route::group(['middleware' => 'auth:api', 'prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::post('profile/driving-license', [DrivingLicenseController::class, 'store']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    // Addresses
    Route::get('addresses', [AddressController::class, 'index']);
    Route::post('addresses', [AddressController::class, 'store']);
    Route::put('addresses/{id}', [AddressController::class, 'update']);
    Route::delete('addresses/{id}', [AddressController::class, 'destroy']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications', [NotificationController::class, 'store']);
    Route::put('notifications/read-all', [NotificationController::class, 'readAll']);
    Route::put('notifications/{id}', [NotificationController::class, 'update']);
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);

    // Wallet
    Route::get('wallet', [WalletController::class, 'getWalletDetails']);
    Route::post('wallet/withdraw', [WalletController::class, 'withdraw']);
    Route::post('wallet/withdraw-hold', [WalletController::class, 'withdrawHold']);

    // Payment creation
    Route::post('vnpay/create-payment', [VNPayController::class, 'createPayment']);
    Route::post('zalopay/create-payment', [ZaloPayController::class, 'createPayment']);
    Route::get('sepay/payment-info', [SeePayController::class, 'getPaymentInfo']);
    Route::get('sepay/check-status', [SeePayController::class, 'checkStatus']);

    // AI Chatbot
    Route::get('chatbot', [AgentController::class, 'index']);
    Route::post('chatbot', [AgentController::class, 'store']);

    // View History
    Route::get('view-histories', [ViewHistoryController::class, 'index']);
    Route::post('view-histories', [ViewHistoryController::class, 'store']);
    Route::delete('view-histories/{carId}', [ViewHistoryController::class, 'destroy']);
});

// Feature routes (prefix: /api/...)
Route::group(['middleware' => 'auth:api'], function () {
    // Favorites
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites', [FavoriteController::class, 'store']);
    Route::delete('favorites/{car_id}', [FavoriteController::class, 'destroy']);

    // Cars management
    Route::post('cars', [CarController::class, 'store']);
    Route::put('cars/{id}', [CarController::class, 'update']);
    Route::patch('cars/{id}/status', [CarController::class, 'updateStatus']);

    // Trips
    Route::get('trips', [TripController::class, 'index']);
    Route::post('trips', [TripController::class, 'store']);
    Route::get('trips/{id}', [TripController::class, 'show']);
    Route::post('trips/{id}/start', [TripController::class, 'startTrip']);
    Route::put('trips/{id}/confirm', [TripController::class, 'confirm']);
    Route::put('trips/{id}/reject', [TripController::class, 'reject']);
    Route::post('trips/{id}/cancel', [TripController::class, 'cancelTrip']);
    Route::post('trips/{id}/owner-cancel', [TripController::class, 'cancelTripByOwner']);
    Route::post('trips/{id}/extension-request', [ExtensionTripController::class, 'requestExtension']);
    Route::put('trips/{id}/extension-approve', [ExtensionTripController::class, 'approveExtension']);
    Route::put('trips/{id}/extension-reject', [ExtensionTripController::class, 'rejectExtension']);
    Route::post('trips/{id}/return-request', [TripController::class, 'requestReturn']);
    Route::post('trips/{id}/complete', [TripController::class, 'completeTrip']);
    Route::post('trips/{id}/reviews', [TripController::class, 'storeReview']);
    Route::post('trips/{id}/extension-pay', [ExtensionTripController::class, 'payExtension']);
    Route::get('my-trips', [MyTripController::class, 'index']);

    // Reports
    Route::post('reports', [ReportController::class, 'store']);

    // Promotions management
    Route::post('promotions/check', [PromotionController::class, 'check']);
    Route::post('promotions', [PromotionController::class, 'store']);
    Route::put('promotions/{id}', [PromotionController::class, 'update']);
    Route::delete('promotions/{id}', [PromotionController::class, 'destroy']);

    // Calendar & Dashboard
    Route::get('car-calendar', [CarCalendarController::class, 'index']);
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Chat
    Route::get('conversations', [ChatController::class, 'index']);
    Route::post('conversations', [ChatController::class, 'storeConversation']);
    Route::get('messages/{id}', [ChatController::class, 'getMessages']);
    Route::post('messages', [ChatController::class, 'storeMessage']);
    Route::put('conversations/{id}/read', [ChatController::class, 'markAsRead']);

    // Broadcasting
    Broadcast::routes(['middleware' => ['api', 'auth:api']]);
});
