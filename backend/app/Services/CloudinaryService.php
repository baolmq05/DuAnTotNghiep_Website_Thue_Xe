<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CloudinaryService
{
    /**
     * Upload ảnh lên Cloudinary từ UploadedFile hoặc giữ nguyên chuỗi URL.
     *
     * @param UploadedFile|string|null $fileOrUrl
     * @param string $folder Thư mục lưu trên Cloudinary
     * @return string|null
     * @throws Exception
     */
    public static function upload($fileOrUrl, string $folder = 'licenses'): ?string
    {
        if (!$fileOrUrl) {
            return null;
        }

        // Nếu đã là chuỗi URL hợp lệ thì trả về luôn
        if (is_string($fileOrUrl)) {
            return $fileOrUrl;
        }

        // Nếu là File upload thực sự (UploadedFile)
        if ($fileOrUrl instanceof UploadedFile) {
            $cloudName = env('CLOUDINARY_CLOUD_NAME', 'djbobb5oe');
            $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET', 'Drivio');

            $response = Http::attach(
                'file',
                file_get_contents($fileOrUrl->getRealPath()),
                $fileOrUrl->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                'upload_preset' => $uploadPreset,
                'folder'        => $folder,
            ]);

            if ($response->successful()) {
                return $response->json('secure_url');
            }

            throw new Exception('Không thể upload ảnh lên Cloudinary.');
        }

        return null;
    }

    /**
     * Xóa hình ảnh trên Cloudinary bằng URL
     *
     * @param string|null $url
     * @return bool
     */
    public static function delete(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'djbobb5oe');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$apiKey || !$apiSecret) {
            Log::warning("Cloudinary credentials missing, skipped deleting image: " . $url);
            return false;
        }

        // Tách public ID từ URL
        $parts = explode('/image/upload/', $url);
        if (count($parts) === 2) {
            $path = $parts[1];
            // Loại bỏ version dạng v1234567/
            $path = preg_replace('/^v\d+\//', '', $path);
            // Loại bỏ phần mở rộng (ví dụ: .jpg)
            $pathParts = explode('.', $path);
            array_pop($pathParts);
            $publicId = implode('.', $pathParts);

            $timestamp = time();
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];

            // Tạo chữ ký (signature)
            ksort($params);
            $signString = "";
            foreach ($params as $key => $val) {
                $signString .= "{$key}={$val}&";
            }
            $signString = rtrim($signString, '&') . $apiSecret;
            $signature = sha1($signString);

            try {
                $response = Http::post("https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy", [
                    'public_id' => $publicId,
                    'timestamp' => $timestamp,
                    'api_key'   => $apiKey,
                    'signature' => $signature,
                ]);

                if ($response->successful()) {
                    Log::info("Deleted from Cloudinary: " . $publicId);
                    return true;
                } else {
                    Log::error("Failed to delete from Cloudinary: " . $response->body());
                }
            } catch (Exception $e) {
                Log::error("Error deleting from Cloudinary: " . $e->getMessage());
            }
        }

        return false;
    }
}
