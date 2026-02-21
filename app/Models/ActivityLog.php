<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'description', 'changes', 'ip_address',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $description, $model = null, ?array $changes = null): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'status_change' => '🔄 تغییر وضعیت',
            'device_assigned' => '📱 اختصاص دستگاه',
            'device_transfer' => '🔀 جابجایی دستگاه',
            'installation_report' => '🔧 گزارش نصب',
            'installation_failed' => '❌ عدم نصب',
            'device_created' => '📦 ثبت دستگاه',
            'device_deleted' => '🗑️ حذف دستگاه',
            'device_faulty' => '⚠️ دستگاه معیوب',
            'installer_created' => '👤 ثبت نصاب',
            'installer_deleted' => '🗑️ حذف نصاب',
            'registration_created' => '📝 ثبت‌نام جدید',
            'registration_deleted' => '🗑️ حذف ثبت‌نام',
            'financial_approved' => '✅ تأیید مالی',
            'financial_rejected' => '❌ رد مالی',
            'preparation_approved' => '✅ تأیید آماده‌سازی',
            'installer_assigned' => '🔧 انتقال به نصاب',
            'relocation_requested' => '🔄 درخواست جابجایی',
            'auto_assign' => '⚡ اختصاص خودکار',
            default => $this->action,
        };
    }
}