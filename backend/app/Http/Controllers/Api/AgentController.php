<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Ai\Agents\DrivioAgent;
use Laravel\Ai\Enums\Lab;

class AgentController extends Controller
{
    public function index()
    {
        try {
            $user = User::first();
            // Lấy cuộc hội thoại mới nhất cùng với danh sách tin nhắn
            $res = AgentConversation::with('messages')
                ->where('user_id', $user->id)
                ->latest('updated_at')
                ->first();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ',
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
            $user = User::first();
            $conversationId = $request->input('conversationId');
            $message = $request->input('message');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ',
                'error' => $e->getMessage()
            ], 401);
        }

        $response = (new DrivioAgent())
            ->continue($conversationId, as: $user)
            ->prompt($message, provider: Lab::Gemini, model: 'gemini-2.5-flash');

        return response($response->text);
    }
}