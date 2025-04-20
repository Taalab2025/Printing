<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
        public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
 * Check if the user is a vendor
 *
 * @return bool
 */
public function isVendor()
{
    // Check if there's a role column in the users table
    if (isset($this->role)) {
        return $this->role === 'vendor';
    }
    
    // Alternative: check if there's a role_id column
    if (isset($this->role_id)) {
        return $this->role_id === 2; // Assuming 2 is the vendor role ID
    }
    
    // If you have a roles relationship
    if (method_exists($this, 'roles')) {
        return $this->roles()->where('name', 'vendor')->exists();
    }
    
    // Default fallback
    return false;
}

/**
 * Check if the user is an admin
 *
 * @return bool
 */
public function isAdmin()
{
    // Check if there's a role column in the users table
    if (isset($this->role)) {
        return $this->role === 'admin';
    }
    
    // Alternative: check if there's a role_id column
    if (isset($this->role_id)) {
        return $this->role_id === 1; // Assuming 1 is the admin role ID
    }
    
    // If you have a roles relationship
    if (method_exists($this, 'roles')) {
        return $this->roles()->where('name', 'admin')->exists();
    }
    
    // Default fallback
    return false;
}


}
