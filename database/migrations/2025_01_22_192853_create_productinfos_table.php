<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productinfos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('Foreign key referencing the productcategories table');
            $table->unsignedBigInteger('product_id')->comment('Foreign key referencing the products table');
            $table->string('property')->comment('Name of the property, e.g., Color, Size');
            $table->text('value')->comment('Value of the property, e.g., Red, Large');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')
                  ->references('id')
                  ->on('productcategories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productinfos');
    }
};
