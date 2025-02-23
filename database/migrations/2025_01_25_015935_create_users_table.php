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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // User's name
            $table->string('phone')->unique(); // User's phone number (unique)
            $table->unsignedBigInteger('role_id'); // Foreign key to roles table
            $table->unsignedBigInteger('village_id'); // Foreign key to villages table
            $table->string('password'); // User's password
            $table->string('profile_image')->nullable(); // New column for profile image (nullable)
            $table->rememberToken(); // Remember token for "remember me" functionality
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key to roles table
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            // Foreign key to villages table
            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onDelete('cascade');
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['role_id']);
            $table->dropForeign(['village_id']);
        });

        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
