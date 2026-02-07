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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // نام
            $table->string('last_name');  // نام خانوادگی
            $table->string('phone_number')->unique(); // شماره تلفن
            $table->date('birth_date')->nullable(); // تاریخ تولد
            $table->boolean('is_new')->default(true); // فیلد جدید بودن (isNew)
            $table->timestamps(); // این به صورت خودکار created_at را برای تاریخ عضویت می‌سازد
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
