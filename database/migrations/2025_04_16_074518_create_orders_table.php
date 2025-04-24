<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('guest_token')->nullable();

            $table->decimal('total_amount', 10, 2);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('gst_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);

            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();

            $table->enum('order_status', ['pending', 'payment-success', 'cancelled', 'out-for-delivery', 'returned'])->default('pending');
            $table->text('note')->nullable();
            $table->boolean('billing_same_as_delivery')->default(false);

            // Delivery Address
            $table->string('d_fname');
            $table->string('d_lname')->nullable();
            $table->string('d_company')->nullable();
            $table->string('d_address_line_1');
            $table->string('d_address_line_2')->nullable();
            $table->string('d_city');
            $table->string('d_state');
            $table->string('d_country');
            $table->string('d_pin_code');
            $table->string('d_pnum');

            // Billing Address
            $table->string('b_fname');
            $table->string('b_lname')->nullable();
            $table->string('b_company')->nullable();
            $table->string('b_address_line_1');
            $table->string('b_address_line_2')->nullable();
            $table->string('b_city');
            $table->string('b_state');
            $table->string('b_country');
            $table->string('b_pin_code');
            $table->string('b_pnum');

            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
