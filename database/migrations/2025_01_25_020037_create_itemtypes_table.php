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
        Schema::create('itemtypes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); 
            $table->unsignedBigInteger('category_id'); // Item type name
            $table->unsignedBigInteger('created_by'); // Foreign key to users table (who created the item type)
            $table->unsignedBigInteger('updated_by')->nullable(); // Foreign key to users table (who updated the item type, nullable)
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key to users table for created_by
            $table->foreign('category_id')
            ->references('id')
            ->on('categories')
            ->onDelete('cascade');
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Foreign key to users table for updated_by
            $table->foreign('updated_by')
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
        Schema::table('itemtypes', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['category_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('itemtypes');
    }
};