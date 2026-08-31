<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Gửi Push Notification qua Google Firebase Cloud Messaging (HTTP v1 API)
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public static function sendPushNotification(string $fcmToken, string $title, string $body, array $data = []): bool
    {
        if (empty($fcmToken)) {
            return false;
        }

        try {
            $credentials = self::getCredentials();

            if (!$credentials || empty($credentials['client_email']) || empty($credentials['private_key'])) {
                Log::info('FCM Notification skipped: Chưa cấu hình credentials trong .env hoặc file json.');
                return false;
            }

            $projectId = $credentials['project_id'] ?? env('FIREBASE_PROJECT_ID', 'thuexethongbao');

            // Lấy Google OAuth2 Access Token
            $accessToken = self::getGoogleAccessToken($credentials);
            if (!$accessToken) {
                Log::error('FCM: Không thể tạo Google Access Token.');
                return false;
            }

            // Gửi thông báo qua FCM HTTP v1 API
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            // Ép kiểu data sang string theo yêu cầu của Google FCM
            $formattedData = [];
            foreach ($data as $k => $v) {
                $formattedData[(string) $k] = is_array($v) ? json_encode($v) : (string) $v;
            }

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $formattedData,
                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'high_importance_channel',
                            'icon' => 'ic_notification',
                            'color' => '#005BAA',
                            'default_sound' => true,
                            'default_vibrate_timings' => true,
                        ],
                    ],
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json; UTF-8'])
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('FCM: Đã gửi thông báo thành công tới token ' . substr($fcmToken, 0, 15) . '...');
                return true;
            } else {
                Log::warning('FCM: Gửi thông báo thất bại: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi gửi FCM Notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin Credentials từ biến môi trường .env hoặc file JSON
     */
    private static function getCredentials(): ?array
    {
        // 1. Kiểm tra Base64 trong .env (Tối ưu nhất cho Railway & Heroku)
        if ($base64 = env('FIREBASE_CREDENTIALS_BASE64')) {
            $decoded = json_decode(base64_decode($base64), true);
            if ($decoded && !empty($decoded['private_key'])) {
                return $decoded;
            }
        }

        // 2. Kiểm tra chuỗi JSON trong .env
        if ($json = env('FIREBASE_CREDENTIALS_JSON')) {
            $decoded = json_decode($json, true);
            if ($decoded && !empty($decoded['private_key'])) {
                return $decoded;
            }
        }

        // 3. Kiểm tra các biến môi trường đơn lẻ trong .env
        if ($privateKey = env('FIREBASE_PRIVATE_KEY')) {
            $privateKey = str_replace('\n', "\n", $privateKey);
            return [
                'project_id' => env('FIREBASE_PROJECT_ID', 'thuexethongbao'),
                'client_email' => env('FIREBASE_CLIENT_EMAIL', 'firebase-adminsdk-fbsvc@thuexethongbao.iam.gserviceaccount.com'),
                'private_key' => $privateKey,
            ];
        }

        // 4. Kiểm tra file vật lý trong storage/app (Fallback cho local)
        $possiblePaths = [
            storage_path('app/firebase_credentials.json'),
            storage_path('app/private/firebase_credentials.json'),
            base_path('firebase_credentials.json'),
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $decoded = json_decode(file_get_contents($path), true);
                if ($decoded && !empty($decoded['private_key'])) {
                    return $decoded;
                }
            }
        }

        return null;
    }

    /**
     * Tạo JWT và lấy OAuth2 Access Token từ Google
     */
    private static function getGoogleAccessToken(array $credentials): ?string
    {
        try {
            $now = time();
            $jwtHeader = self::base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $jwtPayload = self::base64UrlEncode(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ]));

            $signature = '';
            $privateKey = $credentials['private_key'];
            $success = openssl_sign("$jwtHeader.$jwtPayload", $signature, $privateKey, OPENSSL_ALGO_SHA256);

            if (!$success) {
                Log::error('FCM: Lỗi ký OpenSSL với private key.');
                return null;
            }

            $jwt = "$jwtHeader.$jwtPayload." . self::base64UrlEncode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }

            Log::error('FCM: Google OAuth2 trả về lỗi: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Lỗi lấy Google Access Token: ' . $e->getMessage());
            return null;
        }
    }

    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
