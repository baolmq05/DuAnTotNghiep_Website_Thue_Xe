<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Laravel\Ai\Concerns\HasConversations;

#[Fillable(['name', 'email', 'password', 'provider_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasConversations;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->role_id)) {
                $user->role_id = 1; // Mặc định là Admin khi không truyền (ví dụ: chạy lệnh make:filament-user)
            }
            if (empty($user->wallet_id)) {
                $wallet = Wallet::create(['amount' => 0]);
                $user->wallet_id = $wallet->id;
            }
        });

        static::created(function ($user) {
            try {
                $conversationStore = resolve(\Laravel\Ai\Contracts\ConversationStore::class);
                $conversationStore->storeConversation($user->id, 'Trợ lý AI');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi tạo hội thoại AI mặc định cho user ' . $user->id . ': ' . $e->getMessage());
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $with = ['drivingLicense'];
    protected $appends = ['rating'];

    public function getRatingAttribute()
    {
        $rating = \App\Models\Review::where('target_id', $this->id)->avg('rating');
        return $rating ? round(floatval($rating), 1) : 5.0;
    }

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'avatar',
        'gender',
        'DOB',
        'national_number',
        'status',
        'role_id',
        'wallet_id',
        'driving_license_id',
        'provider_id'
    ];

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function drivingLicense(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DrivingLicense::class, 'driving_license_id');
    }

    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function cars(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class, 'user_id');
    }
}
