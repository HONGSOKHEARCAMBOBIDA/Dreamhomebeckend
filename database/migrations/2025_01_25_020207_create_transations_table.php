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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('wh_id'); // Foreign key to warehouses table
            $table->unsignedBigInteger('item_id'); // Foreign key to items table
            $table->integer('qty_in'); // Quantity in
            $table->integer('qty_out'); // Quantity out
            $table->integer('type_transaction'); // Type of transaction (corrected spelling)
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key to warehouses table
            $table->foreign('wh_id')
                  ->references('id')
                  ->on('warehouses')
                  ->onDelete('cascade');

            // Foreign key to items table
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
                  ->onDelete('cascade');

            // Foreign key to users table
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['wh_id']);
            $table->dropForeign(['item_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('transactions');
    }
};