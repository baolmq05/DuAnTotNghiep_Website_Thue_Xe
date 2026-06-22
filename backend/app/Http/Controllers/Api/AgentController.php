<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Ai\Agents\DrivioAgent;
use Laravel\Ai\Enums\Lab;
use Tymon\JWTAuth\Facades\JWTAuth;

class AgentController extends Controller
{
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy cuộc hội thoại mới nhất cùng với danh sách tin nhắn
            $res = AgentConversation::with('messages')
                ->where('user_id', $user->id)
                ->latest('updated_at')
                ->first();

            // Nếu chưa có hội thoại nào, tự động tạo mới cuộc hội thoại mặc định
            if (!$res) {
                try {
                    $conversationStore = resolve(\Laravel\Ai\Contracts\ConversationStore::class);
                    $conversationId = $conversationStore->storeConversation($user->id, 'Trợ lý AI');
                    
                    $res = AgentConversation::with('messages')->find($conversationId);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Lỗi tự động tạo hội thoại AI: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ hoặc token đã hết hạn',
                'error' => $e->getMessage()
            ], 401);
        }

        return response()->json([
            'res' => $res
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }
            $conversationId = $request->input('conversationId');
            $message = $request->input('message');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ hoặc token đã hết hạn',
                'error' => $e->getMessage()
            ], 401);
        }

        $agent = new DrivioAgent();
        if ($conversationId) {
            $agent->continue($conversationId, as: $user);
        } else {
            $agent->forUser($user);
        }

        $response = $agent->prompt($message, provider: Lab::Gemini, model: 'gemini-2.5-flash');

        return response($response->text);
    }
}