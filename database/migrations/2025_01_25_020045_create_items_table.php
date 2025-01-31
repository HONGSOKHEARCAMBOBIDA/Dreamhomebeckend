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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Item name
            $table->unsignedBigInteger('item_type_id'); // Foreign key to itemtypes table
            $table->unsignedBigInteger('measurement_id'); // Foreign key to measurements table
            $table->string('value_measurement'); // Measurement value
            $table->unsignedBigInteger('created_by'); // Foreign key to users table (who created the item)
            $table->unsignedBigInteger('updated_by')->nullable(); // Foreign key to users table (who updated the item, nullable)
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key to users table for created_by
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Foreign key to users table for updated_by
            $table->foreign('updated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Foreign key to itemtypes table
            $table->foreign('item_type_id')
                  ->references('id')
                  ->on('itemtypes')
                  ->onDelete('cascade');

            // Foreign key to measurements table
            $table->foreign('measurement_id')
                  ->references('id')
                  ->on('measurements')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['item_type_id']);
            $table->dropForeign(['measurement_id']);
        });

        Schema::dropIfExists('items');
    }
};