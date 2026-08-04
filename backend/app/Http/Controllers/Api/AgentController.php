<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Ai\Agents\DrivioAgent;
use Laravel\Ai\Enums\Lab;
use Illuminate\Support\Facades\Log;
use Exception;

class AgentController extends Controller
{
    /**
     * Get or create AI conversation for current user.
     * GET /api/auth/chatbot
     */
    public function index()
    {
        try {
            $user = auth('api')->user();

            $res = AgentConversation::with('messages')
                ->where('user_id', $user->id)
                ->latest('updated_at')
                ->first();

            if (!$res) {
                try {
                    $conversationStore = resolve(\Laravel\Ai\Contracts\ConversationStore::class);
                    $conversationId = $conversationStore->storeConversation($user->id, 'Trợ lý AI');

                    $res = AgentConversation::with('messages')->find($conversationId);
                } catch (Exception $e) {
                    Log::error('Lỗi tự động tạo hội thoại AI: ' . $e->getMessage());
                }
            }

            return response()->json([
                'res' => $res
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message to AI chatbot.
     * POST /api/auth/chatbot
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $conversationId = $request->input('conversationId');
        $message = $request->input('message');

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