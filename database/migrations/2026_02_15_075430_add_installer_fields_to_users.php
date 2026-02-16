<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('national_id', 10)->nullable()->after('email');
            $table->string('phone', 11)->nullable()->after('national_id');
            $table->string('organization')->nullable()->after('phone');
            $table->string('province')->nullable()->after('organization');
            $table->string('city')->nullable()->after('province');
            $table->string('address')->nullable()->after('city');
            $table->date('cooperation_start_date')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('cooperation_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'national_id', 'phone', 'organization', 'province',
                'city', 'address', 'cooperation_start_date', 'is_active',
            ]);
        });
    }
};