<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Laravel\Ai\Concerns\HasConversations;
use Laravel\Ai\Contracts\ConversationStore;
use Illuminate\Support\Facades\Log;
use App\Models\Review;
use Exception;

class User extends Authenticatable implements JWTSubject, FilamentUser
{
    /**
     * Determine if the user can access the Filament Admin Panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (int) $this->role_id == 1;
    }

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
        });

        static::created(function ($user) {
            try {
                Wallet::firstOrCreate(
                    ['user_id' => $user->id],
                    ['amount' => 0, 'hold_balance' => 0]
                );
            } catch (Exception $e) {
                Log::error('Lỗi khởi tạo ví cho user ' . $user->id . ': ' . $e->getMessage());
            }

            try {
                $conversationStore = resolve(ConversationStore::class);
                $conversationStore->storeConversation($user->id, 'Trợ lý AI');
            } catch (Exception $e) {
                Log::error('Lỗi tạo hội thoại AI mặc định cho user ' . $user->id . ': ' . $e->getMessage());
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
    protected $appends = ['rating', 'trips_count'];

    public function getTripsCountAttribute()
    {
        return \App\Models\Trip::whereHas('car', function ($q) {
            $q->where('user_id', $this->id);
        })->where('status', \App\Enum\TripStatus::Complete->value)->count();
    }

    public function getRatingAttribute()
    {
        $ownerRating = Review::where('target_id', $this->id)->where('review_type', 1)->avg('rating');
        if ($ownerRating !== null) {
            return round(floatval($ownerRating), 1);
        }

        $renterRating = Review::where('target_id', $this->id)->where('review_type', 0)->avg('rating');
        if ($renterRating !== null) {
            return round(floatval($renterRating), 1);
        }

        return 0.0;
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
        'bank_name',
        'bank_account_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function drivingLicense(): BelongsTo
    {
        return $this->belongsTo(DrivingLicense::class, 'driving_license_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function notifications(): HasMany
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

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'user_id');
    }

    /**
     * Get the penalties received by this user (as owner).
     */
    public function penalties(): HasMany
    {
        return $this->hasMany(OwnerPenalty::class, 'user_id');
    }

    /**
     * Get the penalties resolved/issued by this user (as admin).
     */
    public function resolvedPenalties(): HasMany
    {
        return $this->hasMany(OwnerPenalty::class, 'resolved_by');
    }
}
