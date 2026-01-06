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
        Schema::table('suppliers', function (Blueprint $table) {
            // Keep 'name' field, add comprehensive contact information
            $table->string('contact_person')->nullable()->after('name');
            $table->string('email')->nullable()->after('contact_person');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            
            // Business information
            $table->string('tax_id')->nullable()->after('address');
            
            // Financial tracking
            $table->decimal('credit_limit', 12, 2)->default(0)->after('payment_terms');
            $table->decimal('outstanding_balance', 12, 2)->default(0)->after('credit_limit');
            
            // Status management - using string instead of enum for compatibility
            $table->string('status')->default('active')->after('outstanding_balance');
            
            // Add soft deletes
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('status');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'contact_person',
                'email',
                'phone',
                'address',
                'tax_id',
                'credit_limit',
                'outstanding_balance',
                'status'
            ]);
        });
    }
};
