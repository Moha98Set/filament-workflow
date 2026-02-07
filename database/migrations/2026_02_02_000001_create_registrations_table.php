<?php


/**
 * آدرس فایل: database/migrations/2026_02_02_000001_create_registrations_table.php
 *
 * دستور ایجاد:
 * php artisan make:migration create_registrations_table
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // ============================================
            // اطلاعات استان و سازمان
            // ============================================
            $table->string('province', 100);
            $table->enum('organization', ['jihad', 'industry', 'fisheries']);

            // ============================================
            // اطلاعات شخصی (از فرم)
            // ============================================
            $table->string('full_name', 255);
            $table->string('phone', 11);
            $table->string('national_id', 10)->unique();

            // ============================================
            // اطلاعات محل سکونت (از فرم)
            // ============================================
            $table->string('city', 100);
            $table->string('district', 100);
            $table->string('village', 100);

            // ============================================
            // آدرس نصب (از فرم)
            // ============================================
            $table->text('installation_address');

            // ============================================
            // اطلاعات تراکتورها (JSON - از فرم)
            // ============================================
            $table->json('tractors');

            // ============================================
            // فیلدهای اپراتور (خالی در زمان ثبت‌نام)
            // ============================================

            // وضعیت مالی
            $table->string('financial_status', 500)->nullable()
                ->comment('وضعیت مالی - توسط اپراتور تکمیل می‌شود');

            // وضعیت دستگاه
            $table->string('device_status', 500)->nullable()
                ->comment('وضعیت دستگاه - توسط اپراتور تکمیل می‌شود');

            // نام نصاب
            $table->string('installer_name', 255)->nullable()
                ->comment('نام نصاب - توسط اپراتور تکمیل می‌شود');

            // وضعیت جابجایی
            $table->string('relocation_status', 500)->nullable()
                ->comment('وضعیت جابجایی - توسط اپراتور تکمیل می‌شود');

            // وضعیت مرجوعی
            $table->string('return_status', 500)->nullable()
                ->comment('وضعیت مرجوعی - توسط اپراتور تکمیل می‌شود');

            // کد دستگاه
            $table->string('device_code', 100)->nullable()->unique()
                ->comment('کد دستگاه - توسط اپراتور تکمیل می‌شود');

            // توضیحات
            $table->text('notes')->nullable()
                ->comment('توضیحات - توسط اپراتور تکمیل می‌شود');

            // ============================================
            // وضعیت درخواست
            // ============================================
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending')
                ->comment('pending: در انتظار بررسی, approved: تایید شده, rejected: رد شده, completed: تکمیل شده');

            // یادداشت مدیر (در صورت رد یا توضیحات مدیر)
            $table->text('admin_note')->nullable()
                ->comment('یادداشت مدیر - دلیل رد یا توضیحات');

            // ============================================
            // تاریخچه
            // ============================================

            // کاربری که تایید کرده
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')
                ->comment('کاربری که درخواست را تایید کرده');

            // تاریخ تایید
            $table->timestamp('approved_at')->nullable()
                ->comment('تاریخ تایید درخواست');

            // کاربری که اطلاعات اپراتور را تکمیل کرده
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null')
                ->comment('اپراتوری که اطلاعات را تکمیل کرده');

            // تاریخ تکمیل اطلاعات اپراتور
            $table->timestamp('completed_at')->nullable()
                ->comment('تاریخ تکمیل اطلاعات توسط اپراتور');

            $table->timestamps();

            // ============================================
            // Index ها برای جستجوی سریع‌تر
            // ============================================
            $table->index('province');
            $table->index('organization');
            $table->index('status');
            $table->index('device_code');
            $table->index('national_id');
            $table->index('phone');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
