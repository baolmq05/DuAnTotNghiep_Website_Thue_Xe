<?php

use App\Models\User;
use App\Models\Wallet;
use App\Models\AgentConversation;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(Database\Seeders\RoleSeeder::class);
});

test('creating a user automatically creates a wallet and agent conversation', function () {
    $email = 'test_' . uniqid() . '@example.com';
    $user = User::create([
        'name' => 'Test User',
        'email' => $email,
        'password' => bcrypt('password123'),
        'role_id' => 2,
        'status' => 1,
    ]);

    expect($user->wallet_id)->not->toBeNull();
    expect(Wallet::find($user->wallet_id))->not->toBeNull();

    $conversation = AgentConversation::where('user_id', $user->id)->first();
    expect($conversation)->not->toBeNull();
    expect($conversation->title)->toBe('Trợ lý AI');
});

test('chatbot api returns conversation and works with null conversationId', function () {
    $email = 'test_' . uniqid() . '@example.com';
    $user = User::create([
        'name' => 'Test User',
        'email' => $email,
        'password' => bcrypt('password123'),
        'role_id' => 2,
        'status' => 1,
    ]);

    $token = JWTAuth::fromUser($user);

    // Test GET /api/auth/chatbot
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->getJson('/api/auth/chatbot');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'res' => [
            'id',
            'user_id',
            'title',
        ]
    ]);
});
