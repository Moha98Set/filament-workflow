<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // وضعیت کاربر
            $table->enum('status', ['pending', 'active', 'rejected', 'suspended'])
                ->default('pending')
                ->after('password')
                ->comment('pending: منتظر تایید, active: فعال, rejected: رد شده, suspended: معلق');

            // تگ اپراتور (فقط برای اپراتورها)
            $table->string('operator_tag', 100)
                ->nullable()
                ->after('status')
                ->comment('تگ یا عنوان اپراتور مثل: کارشناس مالی، کارشناس فنی');

            // کاربری که تایید کرده
            $table->foreignId('approved_by')
                ->nullable()
                ->after('operator_tag')
                ->constrained('users')
                ->onDelete('set null')
                ->comment('کاربری که این کاربر را تایید کرده');

            // تاریخ تایید
            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by')
                ->comment('تاریخ تایید کاربر');

            // دلیل رد (اگر رد شده باشد)
            $table->text('rejection_reason')
                ->nullable()
                ->after('approved_at')
                ->comment('دلیل رد ثبت‌نام');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'status',
                'operator_tag',
                'approved_by',
                'approved_at',
                'rejection_reason',
            ]);
        });
    }
};
