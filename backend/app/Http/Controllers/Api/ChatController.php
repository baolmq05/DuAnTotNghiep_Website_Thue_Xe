<?php

namespace App\Http\Controllers\Api;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            $userId = $user->id;

            // Fetch all conversations where the user is either the renter (trip user) or the owner of the car
            $conversations = ChatConversation::whereHas('trip', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhereHas('car', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
            })
                ->with([
                    'trip.user',
                    'trip.car.owner',
                    'trip.car.images',
                    'messages' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                ->get()
                ->map(function ($conversation) use ($userId) {
                    $renter = $conversation->trip->user;
                    $owner = $conversation->trip->car->owner;

                    // The other participant is the owner if the current user is the renter, and vice versa
                    $otherUser = ($userId == $renter->id) ? $owner : $renter;

                    // Get the last message in this conversation
                    $lastMessage = $conversation->messages->first();

                    // Count unread messages sent by the other user
                    $unreadCount = $conversation->messages
                        ->where('sender_id', '!=', $userId)
                        ->where('is_read', false)
                        ->count();

                    return [
                        'id' => $conversation->id,
                        'status' => $conversation->status,
                        'trip_id' => $conversation->trip_id,
                        'created_at' => $conversation->created_at,
                        'updated_at' => $conversation->updated_at,
                        'other_user' => $otherUser ? [
                            'id' => $otherUser->id,
                            'name' => $otherUser->name,
                            'avatar' => $otherUser->avatar,
                        ] : null,
                        'car' => $conversation->trip->car ? [
                            'id' => $conversation->trip->car->id,
                            'name' => $conversation->trip->car->name,
                            'image' => $conversation->trip->car->images->first()?->image_url ?? null,
                        ] : null,
                        'last_message' => $lastMessage ? [
                            'text' => $lastMessage->text,
                            'type' => $lastMessage->type,
                            'sender_id' => $lastMessage->sender_id,
                            'created_at' => $lastMessage->created_at,
                        ] : null,
                        'unread_count' => $unreadCount
                    ];
                });

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy danh sách tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function getMessages(string $id)
    {
        try {
            $conversation = ChatConversation::findOrFail($id);
            $messages = $conversation->messages;
            return response()->json([
                'success' => true,
                'messages' => $messages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function storeConversation(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            $conversation = ChatConversation::create([
                'trip_id' => $request->trip_id,
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'conversation' => $conversation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function storeMessage(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            $message = ChatMessage::create([
                'conversation_id' => $request->conversation_id,
                'sender_id' => $user->id,
                'text' => $request->text,
                'type' => $request->type,
                'is_read' => false,
            ]);

            broadcast(new \App\Events\MessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead(string $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Đánh dấu đã đọc cho tất cả tin nhắn trong hội thoại này của người kia gửi
            ChatMessage::where('conversation_id', $id)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu các tin nhắn là đã đọc'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật trạng thái đã đọc thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
