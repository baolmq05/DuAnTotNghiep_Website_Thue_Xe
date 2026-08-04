<?php

namespace App\Http\Controllers\Api;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Events\MessageSent;
use App\Services\CloudinaryService;
use App\Http\Requests\Chat\StoreMessageRequest;

class ChatController extends Controller
{
    /**
     * Get all conversations for current user.
     * GET /api/conversations
     */
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy danh sách tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages of a conversation.
     * GET /api/messages/{id}
     */
    public function getMessages(string $id)
    {
        try {
            $conversation = ChatConversation::findOrFail($id);
            $messages = $conversation->messages;
            return response()->json([
                'success' => true,
                'messages' => $messages
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or get a conversation for a trip.
     * POST /api/conversations
     */
    public function storeConversation(Request $request)
    {
        try {
            $conversation = ChatConversation::where('trip_id', $request->trip_id)->first();
            if (!$conversation) {
                $conversation = ChatConversation::create([
                    'trip_id' => $request->trip_id,
                    'status' => 1,
                ]);
            }

            return response()->json([
                'success' => true,
                'conversation' => $conversation
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a new message.
     * POST /api/messages
     */
    public function storeMessage(StoreMessageRequest $request)
    {
        try {
            $user = auth('api')->user();
            $messageText = $request->input('text');

            if ($request->input('type') === 'image' && $request->hasFile('image')) {
                $messageText = CloudinaryService::upload($request->file('image'), 'chats');
            }

            $message = ChatMessage::create([
                'conversation_id' => $request->conversation_id,
                'sender_id' => $user->id,
                'text' => $messageText,
                'type' => $request->type,
                'is_read' => false,
            ]);

            // BroadCast
            broadcast(new MessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi tin nhắn thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark messages in a conversation as read.
     * PUT /api/conversations/{id}/read
     */
    public function markAsRead(string $id)
    {
        try {
            $user = auth('api')->user();

            // MarkAsRead
            ChatMessage::where('conversation_id', $id)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu các tin nhắn là đã đọc'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật trạng thái đã đọc thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
