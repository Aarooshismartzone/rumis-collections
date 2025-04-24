<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'guest_token',
        'total_amount',
        'delivery_charge',
        'gst_amount',
        'grand_total',
        'payment_method',
        'payment_id',
        'order_status',
        'note',
        'billing_same_as_delivery',
        // Delivery address
        'd_fname',
        'd_lname',
        'd_company',
        'd_address_line_1',
        'd_address_line_2',
        'd_city',
        'd_state',
        'd_country',
        'd_pin_code',
        'd_pnum',
        // Billing address
        'b_fname',
        'b_lname',
        'b_company',
        'b_address_line_1',
        'b_address_line_2',
        'b_city',
        'b_state',
        'b_country',
        'b_pin_code',
        'b_pnum',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
