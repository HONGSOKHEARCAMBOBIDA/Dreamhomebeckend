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
        Schema::create('wharehouses', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Corrected: lowercase 'string'
            $table->integer('type_warehouse'); // Corrected spelling
            $table->unsignedBigInteger('village_id'); // Foreign key to villages table
            $table->integer('status')->default(1); // Added default value for status
            $table->unsignedBigInteger('created_by'); // Corrected column name
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key to villages table
            $table->foreign('village_id')
                  ->references('id')
                  ->on('villages')
                  ->onDelete('cascade');

            // Foreign key to users table
            $table->foreign('created_by')
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
        Schema::table('warehouses', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['village_id']);
            $table->dropForeign(['created_by']);
        });

        Schema::dropIfExists('warehouses');
    }
};