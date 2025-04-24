<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'payment_method',
        'currency_name',
        'amount',
        'payment_id',
    ];

    /**
     * Relationship: Payment belongs to an Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
