<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->boolean('sim_activated')->default(false)->after('status');
            $table->boolean('device_tested')->default(false)->after('sim_activated');
            $table->foreignId('preparation_approved_by')->nullable()->after('device_tested')->constrained('users')->nullOnDelete();
            $table->timestamp('preparation_approved_at')->nullable()->after('preparation_approved_by');
            $table->text('preparation_note')->nullable()->after('preparation_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'sim_activated',
                'device_tested',
                'preparation_approved_by',
                'preparation_approved_at',
                'preparation_note',
            ]);
        });
    }
};