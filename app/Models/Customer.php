<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'fname',
        'lname',
        'username',
        'email',
        'pnum',
        'password',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'pin_code',
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
