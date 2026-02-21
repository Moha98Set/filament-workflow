<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('registrations', function (Blueprint $table) {
        if (!Schema::hasColumn('registrations', 'payment_method')) {
            $table->string('payment_method')->nullable()->after('status');
        }
        if (!Schema::hasColumn('registrations', 'payment_status')) {
            $table->string('payment_status')->default('unpaid')->after('payment_method');
        }
        if (!Schema::hasColumn('registrations', 'payment_track_id')) {
            $table->string('payment_track_id')->nullable()->after('payment_receipt');
        }
        if (!Schema::hasColumn('registrations', 'payment_ref_number')) {
            $table->string('payment_ref_number')->nullable()->after('payment_track_id');
        }
        if (!Schema::hasColumn('registrations', 'payment_amount')) {
            $table->decimal('payment_amount', 12, 0)->default(0)->after('payment_ref_number');
        }
        if (!Schema::hasColumn('registrations', 'payment_verified_at')) {
            $table->timestamp('payment_verified_at')->nullable()->after('payment_amount');
        }
        if (!Schema::hasColumn('registrations', 'contract_accepted')) {
            $table->boolean('contract_accepted')->default(false)->after('payment_verified_at');
        }
        if (!Schema::hasColumn('registrations', 'contract_accepted_at')) {
            $table->timestamp('contract_accepted_at')->nullable()->after('contract_accepted');
        }
    });
}

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_status', 'payment_receipt',
                'payment_track_id', 'payment_ref_number', 'payment_amount',
                'payment_verified_at', 'contract_accepted', 'contract_accepted_at',
            ]);
        });
    }
};