<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('sim_number')->nullable()->after('serial_number');
            $table->string('sim_serial')->nullable()->after('sim_number');
            $table->boolean('has_sim')->default(false)->after('sim_serial');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['sim_number', 'sim_serial', 'has_sim']);
        });
    }
};