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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity'); // Positive for addition, negative for deduction
            $table->string('type'); // purchase, sale, adjustment, return, initial
            $table->nullableMorphs('reference'); // For purchase_id, sale_id linking
            $table->foreignId('user_id')->constrained();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
