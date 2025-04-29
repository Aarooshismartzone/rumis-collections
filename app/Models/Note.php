<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'notes';

    // Mass assignable fields
    protected $fillable = [
        'customer_id',
        'guest_token',
        'order_id',
        'related_to',
        'note',
        'is_manual',
        'user_id', //nullable
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
