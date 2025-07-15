<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
        'last_activity_at',
        'email_verified_at',
        'profile_image',
        'date_of_birth',
        'gender',
        'preferences',
        'avatar',
        'language',
        'currency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
            'date_of_birth' => 'date',
            'preferences' => 'array',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is moderator
     */
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user account is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user has verified email
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Get user's permissions based on role
     */
    public function getPermissions(): array
    {
        $permissions = [
            'user' => [
                'view_products',
                'add_to_cart',
                'place_orders',
                'view_own_orders',
                'edit_profile',
                'view_own_reviews',
                'create_reviews',
            ],
            'moderator' => [
                'view_products',
                'add_to_cart',
                'place_orders',
                'view_own_orders',
                'edit_profile',
                'view_own_reviews',
                'create_reviews',
                'view_orders',
                'view_users',
            ],
            'admin' => [
                'view_products',
                'add_to_cart',
                'place_orders',
                'view_own_orders',
                'edit_profile',
                'view_own_reviews',
                'create_reviews',
                'moderate_reviews',
                'view_users',
                'edit_products',
                'view_orders',
                'manage_users',
                'manage_products',
                'manage_categories',
                'manage_orders',
                'view_analytics',
                'manage_settings',
                'manage_roles',
                'view_logs',
                'manage_backups',
                'create_products',
                'update_products',
                'delete_products',
                'create_categories',
                'update_categories',
                'delete_categories',
                'update_orders',
                'delete_orders',
                'create_users',
                'update_users',
                'delete_users',
            ]
        ];

        return $permissions[$this->role] ?? [];
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return !empty(array_intersect($permissions, $this->getPermissions()));
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return empty(array_diff($permissions, $this->getPermissions()));
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin' => 'مدير',
            'moderator' => 'مشرف',
            'user' => 'مستخدم',
            default => 'غير محدد'
        };
    }

    /**
     * Get user's status display name
     */
    public function getStatusDisplayName(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'suspended' => 'معلق',
            'inactive' => 'غير نشط',
            default => 'غير محدد'
        };
    }

    /**
     * Get user's age
     */
    public function getAge(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Get user's full name
     */
    public function getFullName(): string
    {
        return $this->name;
    }

    /**
     * Get user's initials
     */
    public function getInitials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= mb_substr($word, 0, 1, 'UTF-8');
        }
        
        return mb_strtoupper($initials, 'UTF-8');
    }

    /**
     * Get user's profile image URL
     */
    public function getProfileImageUrl(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Return default avatar with initials
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=6366f1&background=f3f4f6&size=200";
    }

    /**
     * Get user's cart items
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get user's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get user's wishlist
     */
    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlist')->withTimestamps();
    }

    /**
     * Get user's recent activity
     */
    public function recentActivity()
    {
        return $this->hasMany(UserActivity::class)->latest();
    }

    /**
     * Check if user can place orders
     */
    public function canPlaceOrders(): bool
    {
        return $this->isActive() && $this->hasVerifiedEmail();
    }

    /**
     * Check if user can add to cart
     */
    public function canAddToCart(): bool
    {
        return $this->isActive();
    }

    /**
     * Get user's total spent
     */
    public function getTotalSpent(): float
    {
        return $this->orders()
            ->where('status', 'delivered')
            ->sum('total_amount');
    }

    /**
     * Get user's order count
     */
    public function getOrderCount(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get user's cart items count
     */
    public function getCartItemsCount(): int
    {
        return $this->cartItems()->sum('quantity');
    }

    /**
     * Check if user is online (active in last 5 minutes)
     */
    public function isOnline(): bool
    {
        return $this->last_activity_at && $this->last_activity_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Get user's last activity time
     */
    public function getLastActivityTime(): string
    {
        if (!$this->last_activity_at) {
            return 'لم يسجل نشاط';
        }

        $diff = $this->last_activity_at->diffForHumans();
        return $diff;
    }

    /**
     * Update user's last activity
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Suspend user account
     */
    public function suspend(string $reason = null): void
    {
        $this->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspension_reason' => $reason
        ]);
    }

    /**
     * Activate user account
     */
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'suspended_at' => null,
            'suspension_reason' => null
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(string $newPassword): void
    {
        $this->update(['password' => Hash::make($newPassword)]);
    }

    /**
     * Get user's preferences
     */
    public function getPreference(string $key, $default = null)
    {
        return $this->preferences[$key] ?? $default;
    }

    /**
     * Set user's preference
     */
    public function setPreference(string $key, $value): void
    {
        $preferences = $this->preferences ?? [];
        $preferences[$key] = $value;
        $this->update(['preferences' => $preferences]);
    }
}
