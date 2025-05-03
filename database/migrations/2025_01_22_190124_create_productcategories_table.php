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
        Schema::create('productcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_category')->nullable()->comment('ID of the parent category, nullable for top-level categories');
            $table->string('category_name')->comment('Name of the category');
            $table->string('category_slug')->unique()->comment('Unique slug for the category, used for URLs');
            $table->boolean('is_productsize')->default(false);
            $table->timestamps();

            // Foreign key for parent_category, referencing id in the same table
            $table->foreign('parent_category')
                  ->references('id')
                  ->on('productcategories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productcategories');
    }
};
