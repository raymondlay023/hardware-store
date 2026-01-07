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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'products.create', 'sales.view'
            $table->string('display_name'); // e.g., 'Create Products'
            $table->string('description')->nullable(); // Human-readable description
            $table->string('category')->nullable(); // e.g., 'products', 'sales', 'reports'
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
