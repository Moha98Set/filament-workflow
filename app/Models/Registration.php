<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'province',
        'organization',
        'full_name',
        'phone',
        'national_id',
        'city',
        'district',
        'village',
        'installation_address',
        'tractors',
        'status',
        
        // بخش مالی
        'financial_approved_by',
        'financial_approved_at',
        'financial_note',
        'payment_receipt',
        'payment_amount',
        'transaction_id',
        'payment_method',
        'payment_status',
        'payment_track_id',
        'payment_ref_number',
        'payment_verified_at',
        'contract_accepted',
        'contract_accepted_at',
        
        // بخش فنی
        'device_assigned_by',
        'device_assigned_at',
        'assigned_device_id',
        'device_assignment_note',
        'sim_activated',
        'device_tested',
        'preparation_approved_by',
        'preparation_approved_at',
        'preparation_note',
        
        // بخش نصاب
        'installer_id',
        'installation_scheduled_at',
        'installation_completed_at',
        'installation_note',
        'installation_photos',
        
        // مرجوعی و جابجایی
        'is_returned',
        'return_reason',
        'returned_at',
        'is_relocated',
        'previous_registration_id',
        
        // فیلدهای قدیمی اپراتور
        'financial_status',
        'device_status',
        'installer_name',
        'relocation_status',
        'return_status',
        'device_code',
        'notes',
        'admin_note',
        'approved_by',
        'approved_at',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'tractors' => 'array',
        'installation_photos' => 'array',
        'financial_approved_at' => 'datetime',
        'device_assigned_at' => 'datetime',
        'installation_scheduled_at' => 'datetime',
        'installation_completed_at' => 'datetime',
        'returned_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'payment_amount' => 'decimal:2',
        'is_returned' => 'boolean',
        'is_relocated' => 'boolean',
        'sim_activated' => 'boolean',
        'device_tested' => 'boolean',
        'preparation_approved_at' => 'datetime',
    ];

    // ========================================
    // روابط
    // ========================================

    public function preparationApprover()
    {
        return $this->belongsTo(User::class, 'preparation_approved_by');
    }
    public function financialApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financial_approved_by');
    }

    public function deviceAssigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'device_assigned_by');
    }

    public function installer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'installer_id');
    }

    public function assignedDevice(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'assigned_device_id');
    }

    public function previousRegistration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'previous_registration_id');
    }

    // ========================================
    // Scopes
    // ========================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFinancialApproved($query)
    {
        return $query->where('status', 'financial_approved');
    }

    public function scopeDeviceAssigned($query)
    {
        return $query->where('status', 'device_assigned');
    }

    public function scopeReadyForInstallation($query)
    {
        return $query->where('status', 'ready_for_installation');
    }

    public function scopeInstalled($query)
    {
        return $query->where('status', 'installed');
    }

    public function scopeReturned($query)
    {
        return $query->where('is_returned', true);
    }

    // ========================================
    // Accessors
    // ========================================

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => '⏳ در انتظار بررسی مالی',
            'financial_approved' => '✅ تایید مالی شده',
            'financial_rejected' => '❌ رد مالی',
            'device_assigned' => '📱 دستگاه اختصاص داده شد',
            'ready_for_installation' => '🔧 آماده نصب',
            'installed' => '✅ نصب شده',
            'installation_failed' => '❌ نصب ناموفق',
            'relocation_requested' => 'درخواست جابجایی',
            'relocation_requested' => '🔄 درخواست جابجایی',
            default => 'نامشخص',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'financial_approved' => 'success',
            'financial_rejected' => 'danger',
            'device_assigned' => 'info',
            'ready_for_installation' => 'primary',
            'installed' => 'success',
            'installation_failed' => 'danger',
            'relocation_requested' => 'warning',
            'returned' => 'secondary',
            default => 'gray',
        };
    }

    // ========================================
    // متدهای کمکی
    // ========================================

    public function canFinancialApprove(): bool
    {
        return $this->status === 'pending';
    }

    public function canAssignDevice(): bool
    {
        return $this->status === 'financial_approved';
    }

    public function canInstall(): bool
    {
        return in_array($this->status, ['device_assigned', 'ready_for_installation']);
    }

    public function markAsFinancialApproved(User $approver, array $data = []): void
    {
        $this->update([
            'status' => 'financial_approved',
            'financial_approved_by' => $approver->id,
            'financial_approved_at' => now(),
            'financial_note' => $data['note'] ?? null,
            'payment_amount' => $data['amount'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
        ]);
    }

    public function assignDevice(User $assigner, Device $device, string $note = null): void
    {
        $this->update([
            'status' => 'device_assigned',
            'device_assigned_by' => $assigner->id,
            'device_assigned_at' => now(),
            'assigned_device_id' => $device->id,
            'device_assignment_note' => $note,
        ]);

        $device->update([
            'status' => 'assigned',
            'assigned_to_registration_id' => $this->id,
        ]);
    }

    public function markAsInstalled(User $installer, array $data = []): void
    {
        $this->update([
            'status' => 'installed',
            'installation_completed_at' => now(),
            'installation_note' => $data['note'] ?? null,
            'installation_photos' => $data['photos'] ?? [],
        ]);

        if ($this->assignedDevice) {
            $this->assignedDevice->update(['status' => 'installed']);
        }
    }
}