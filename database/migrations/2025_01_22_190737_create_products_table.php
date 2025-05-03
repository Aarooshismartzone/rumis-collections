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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_category_id')->comment('Foreign key referencing the productcategories table');
            $table->string('product_name')->comment('Name of the product');
            $table->string('product_slug')->unique()->comment('Unique slug for the product, used for URLs');
            $table->text('description')->nullable()->comment('Detailed description of the product');
            $table->string('image')->comment('Path to the product image');
            $table->string('ai_1')->comment('Additional image 1');
            $table->string('ai_2')->comment('Additional image 2');
            $table->string('ai_3')->comment('Additional image 3');
            $table->string('ai_4')->comment('Additional image 4');
            $table->string('ai_5')->comment('Additional image 5');
            $table->string('ai_6')->comment('Additional image 6');
            $table->text('product_size')->nullable();
            $table->decimal('actual_price', 10, 2)->comment('Original price of the product');
            $table->decimal('discounted_price', 10, 2)->nullable()->comment('Discounted price of the product');
            $table->boolean('sale')->default(false)->comment('Indicates if the product is on sale');
            $table->integer('stock')->default(0)->comment('Available stock quantity');
            $table->string('sku')->unique()->nullable()->comment('Unique Stock Keeping Unit for the product');
            $table->integer('views')->default(0)->comment('Number of times the product was viewed');
            $table->boolean('is_featured')->default(false)->comment('Indicates if the product is featured');
            $table->integer('number_of_orders')->default(0)->comment('Number of items ordered')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('product_category_id')
                  ->references('id')
                  ->on('productcategories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        // Create the `tags` table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Name of the tag');
            $table->string('slug')->unique()->comment('Slug for the tag');
            $table->timestamps();
        });

        // Create the pivot table for products and tags
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('Foreign key referencing the products table');
            $table->unsignedBigInteger('tag_id')->comment('Foreign key referencing the tags table');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('tag_id')
                  ->references('id')
                  ->on('tags')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('products');
    }
};
