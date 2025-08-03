<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, SoftDeletes, CanResetPasswordTrait;

    protected $table = 'customers';

    protected $fillable = [
        'fname',
        'lname',
        'username',
        'email',
        'pnum',
        'password',
        'company_name',
        'profile_image',
        'email_verified_at',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ⚠️ Remove the automatic bcrypt to avoid double hashing on reset
    public function setPasswordAttribute($value)
    {
        // Only hash if not already hashed
        if (\Illuminate\Support\Str::startsWith($value, '$2y$') === false) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }
}
