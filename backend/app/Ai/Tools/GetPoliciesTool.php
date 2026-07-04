<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetPoliciesTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Đọc các chính sách của Drivio bao gồm: Chính sách hủy chuyến, chính sách cọc xe, điều khoản thuê xe và phí phát sinh.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $path = storage_path('app/public/policies/rental_policy.md');
        if (file_exists($path)) {
            return file_get_contents($path);
        } else {
            return "Không tìm thấy file chính sách.";
        }
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value' => $schema->string()->required(),
        ];
    }
}
