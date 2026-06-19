<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Test cannot access notifications without authentication
     */
    public function test_cannot_access_notifications_without_auth(): void
    {
        $response = $this->getJson('/api/auth/notifications');

        $response->assertStatus(401);
    }

    /**
     * Test can get notifications list of authenticated user
     */
    public function test_can_get_notifications_with_auth(): void
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        Notification::create([
            'user_id' => $user->id,
            'message' => 'Thông báo thử nghiệm 1',
            'is_read' => '0'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'message',
                        'is_read',
                        'user_id',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * Test can create notification
     */
    public function test_can_create_notification(): void
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/notifications', [
                'message' => 'Thông báo tạo mới',
                'user_id' => $user->id
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Thêm thông báo thành công'
            ]);

        $this->assertDatabaseHas('notifications', [
            'message' => 'Thông báo tạo mới',
            'user_id' => $user->id,
            'is_read' => '0'
        ]);
    }

    /**
     * Test validation failure on creation
     */
    public function test_create_notification_fails_without_message(): void
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/notifications', [
                'user_id' => $user->id
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Dữ liệu thông báo không hợp lệ'
            ]);
    }

    /**
     * Test can update notification read status
     */
    public function test_can_update_notification_read_status(): void
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        $notification = Notification::create([
            'user_id' => $user->id,
            'message' => 'Thông báo cần cập nhật',
            'is_read' => '0'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/auth/notifications/{$notification->id}", [
                'is_read' => '1'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cập nhật thông báo thành công'
            ]);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => '1'
        ]);
    }

    /**
     * Test can mark all notifications as read
     */
    public function test_can_mark_all_notifications_as_read(): void
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        Notification::create(['user_id' => $user->id, 'message' => 'Thông báo 1', 'is_read' => '0']);
        Notification::create(['user_id' => $user->id, 'message' => 'Thông báo 2', 'is_read' => '0']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/auth/notifications/read-all');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Đánh dấu tất cả thông báo là đã đọc thành công'
            ]);

        $unreadCount = Notification::where('user_id', $user->id)->where('is_read', '0')->count();
        $this->assertEquals(0, $unreadCount);
    }

    /**
     * Test can delete notification
     */
    public function test_can_delete_notification(): void
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        $notification = Notification::create([
            'user_id' => $user->id,
            'message' => 'Thông báo cần xóa',
            'is_read' => '0'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/auth/notifications/{$notification->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Xóa thông báo thành công'
            ]);

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id
        ]);
    }
}
