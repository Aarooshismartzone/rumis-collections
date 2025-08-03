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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('fname', 100); // First name
            $table->string('lname', 100)->nullable(); // Last name
            $table->string('username', 100)->unique(); // Username
            $table->string('email', 150)->unique(); // Email address
            $table->string('pnum', 15)->unique(); // Phone number
            $table->string('password'); // Encrypted password
            $table->string('company_name', 150)->nullable(); // Optional company name
            $table->string('profile_image')->nullable(); // Profile image path
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->boolean('is_active')->default(true); // Customer active status
            $table->rememberToken(); // Token for "remember me"
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
