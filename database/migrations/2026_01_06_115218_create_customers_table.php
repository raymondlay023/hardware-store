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
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->enum('type', ['retail', 'wholesale', 'contractor'])->default('retail');
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('total_purchases', 12, 2)->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('phone');
            $table->index('type');
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
