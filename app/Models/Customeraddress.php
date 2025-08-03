<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customeraddress extends Model
{
    use HasFactory;

    protected $table = 'customeraddresses';

    protected $fillable = [
        'customer_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'pin_code',
        'is_primary_address',
    ];

    protected $casts = [
        'is_primary_address' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

