<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    /**
     * Get the user's active subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscribe::class);
    }

    /**
     * Get the user's addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the products created by the user (artist).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeIsArtist($query)
    {
        // get from role
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'artist');
        });
    }

    /**
     * Determine if the user can impersonate other users.
     */
    public function canImpersonate(): bool
    {
        // Only admins can impersonate other users
        return $this->hasRole('admin');
    }

    /**
     * Determine if the user can be impersonated.
     */
    public function canBeImpersonated(): bool
    {
        // Admins cannot be impersonated
        return !$this->hasRole('admin');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'artist']);
    }
}
