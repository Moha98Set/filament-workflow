<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ابتدا status قدیمی را حذف می‌کنیم
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'status')) {
                $table->dropColumn('status');
            }
        });

        // حالا ستون‌های جدید را اضافه می‌کنیم
        Schema::table('registrations', function (Blueprint $table) {
            // وضعیت جدید
            $table->enum('status', [
                'pending',
                'financial_approved',
                'financial_rejected',
                'device_assigned',
                'ready_for_installation',
                'installed',
                'installation_failed',
                'returned',
            ])->default('pending')->after('tractors');

            // بخش مالی
            $table->foreignId('financial_approved_by')->nullable()->after('status');
            $table->timestamp('financial_approved_at')->nullable()->after('financial_approved_by');
            $table->text('financial_note')->nullable()->after('financial_approved_at');
            $table->string('payment_receipt', 500)->nullable()->after('financial_note');
            $table->decimal('payment_amount', 15, 2)->nullable()->after('payment_receipt');
            $table->string('transaction_id', 100)->nullable()->after('payment_amount');

            // بخش فنی
            $table->foreignId('device_assigned_by')->nullable()->after('transaction_id');
            $table->timestamp('device_assigned_at')->nullable()->after('device_assigned_by');
            $table->foreignId('assigned_device_id')->nullable()->after('device_assigned_at');
            $table->text('device_assignment_note')->nullable()->after('assigned_device_id');

            // بخش نصاب
            $table->foreignId('installer_id')->nullable()->after('device_assignment_note');
            $table->timestamp('installation_scheduled_at')->nullable()->after('installer_id');
            $table->timestamp('installation_completed_at')->nullable()->after('installation_scheduled_at');
            $table->text('installation_note')->nullable()->after('installation_completed_at');
            $table->json('installation_photos')->nullable()->after('installation_note');

            // مرجوعی و جابجایی
            $table->boolean('is_returned')->default(false)->after('installation_photos');
            $table->text('return_reason')->nullable()->after('is_returned');
            $table->timestamp('returned_at')->nullable()->after('return_reason');
            $table->boolean('is_relocated')->default(false)->after('returned_at');
            $table->foreignId('previous_registration_id')->nullable()->after('is_relocated');
        });

        // اضافه کردن Foreign Keys
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreign('financial_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('device_assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('installer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_device_id')->references('id')->on('devices')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['financial_approved_by']);
            $table->dropForeign(['device_assigned_by']);
            $table->dropForeign(['installer_id']);
            $table->dropForeign(['assigned_device_id']);
            
            $table->dropColumn([
                'status',
                'financial_approved_by',
                'financial_approved_at',
                'financial_note',
                'payment_receipt',
                'payment_amount',
                'transaction_id',
                'device_assigned_by',
                'device_assigned_at',
                'assigned_device_id',
                'device_assignment_note',
                'installer_id',
                'installation_scheduled_at',
                'installation_completed_at',
                'installation_note',
                'installation_photos',
                'is_returned',
                'return_reason',
                'returned_at',
                'is_relocated',
                'previous_registration_id',
            ]);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
        });
    }
};