<?php

/**
 * آدرس فایل: database/migrations/2026_02_05_000001_add_columns_to_devices_table.php
 * 
 * دستور ایجاد:
 * php artisan make:migration add_columns_to_devices_table --table=devices
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // اطلاعات اصلی دستگاه
            $table->string('code', 100)->unique()->after('id')->comment('کد دستگاه - یکتا');
            $table->string('type', 100)->after('code')->comment('نوع دستگاه');
            $table->string('serial_number', 100)->unique()->after('type')->comment('سریال دستگاه');
            $table->date('manufacturing_date')->nullable()->after('serial_number')->comment('تاریخ تولید');
            
            // وضعیت دستگاه
            $table->enum('status', [
                'available',    // موجود و آماده اختصاص
                'assigned',     // اختصاص داده شده
                'installed',    // نصب شده
                'faulty',       // معیوب
                'maintenance',  // در تعمیر
                'returned',     // مرجوع شده
            ])->default('available')->after('manufacturing_date')->comment('وضعیت دستگاه');
            
            // اختصاص به ثبت‌نام
            $table->foreignId('assigned_to_registration_id')
                ->nullable()
                ->after('status')
                ->comment('ثبت‌نامی که دستگاه به آن اختصاص یافته');
            
            // کاربری که دستگاه را ثبت کرده
            $table->foreignId('created_by')
                ->after('assigned_to_registration_id')
                ->comment('اپراتور فنی که دستگاه را ثبت کرده');
            
            // یادداشت‌ها
            $table->text('notes')->nullable()->after('created_by')->comment('یادداشت درباره دستگاه');
            
            // مرجوعی
            $table->boolean('is_returned')->default(false)->after('notes')->comment('آیا مرجوع شده');
            $table->text('return_reason')->nullable()->after('is_returned')->comment('دلیل مرجوعی');
            $table->timestamp('returned_at')->nullable()->after('return_reason')->comment('تاریخ مرجوعی');
            
            // Index ها
            $table->index('code');
            $table->index('serial_number');
            $table->index('status');
            $table->index('assigned_to_registration_id');
            $table->index('is_returned');
        });

        // اضافه کردن Foreign Key بعد از ایجاد ستون‌ها
        Schema::table('devices', function (Blueprint $table) {
            $table->foreign('assigned_to_registration_id')
                ->references('id')
                ->on('registrations')
                ->onDelete('set null');
            
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // حذف Foreign Key ها
            $table->dropForeign(['assigned_to_registration_id']);
            $table->dropForeign(['created_by']);
            
            // حذف Index ها
            $table->dropIndex(['code']);
            $table->dropIndex(['serial_number']);
            $table->dropIndex(['status']);
            $table->dropIndex(['assigned_to_registration_id']);
            $table->dropIndex(['is_returned']);
            
            // حذف ستون‌ها
            $table->dropColumn([
                'code',
                'type',
                'serial_number',
                'manufacturing_date',
                'status',
                'assigned_to_registration_id',
                'created_by',
                'notes',
                'is_returned',
                'return_reason',
                'returned_at',
            ]);
        });
    }
};