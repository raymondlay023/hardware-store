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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('discount_type')->default('none')->after('total_amount');
            $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
            $table->string('payment_method')->default('cash')->after('discount_value');
            $table->text('notes')->nullable()->after('payment_method');
            $table->unsignedBigInteger('created_by')->after('notes');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'payment_method', 'notes', 'created_by']);
        });
    }
};
