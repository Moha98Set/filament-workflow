<?php

/**
 * آدرس فایل: app/Models/Device.php
 * 
 * دستور ایجاد:
 * php artisan make:model Device
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    protected $fillable = [
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
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'returned_at' => 'datetime',
        'is_returned' => 'boolean',
    ];

    // ========================================
    // روابط
    // ========================================

    public function assignedToRegistration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'assigned_to_registration_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ========================================
    // Scopes
    // ========================================

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInstalled($query)
    {
        return $query->where('status', 'installed');
    }

    public function scopeFaulty($query)
    {
        return $query->where('status', 'faulty');
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
            'available' => '✅ موجود',
            'assigned' => '📋 اختصاص داده شده',
            'installed' => '✅ نصب شده',
            'faulty' => '⚠️ معیوب',
            'maintenance' => '🔧 در تعمیر',
            'returned' => '↩️ مرجوع شده',
            default => 'نامشخص',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'success',
            'assigned' => 'info',
            'installed' => 'success',
            'faulty' => 'danger',
            'maintenance' => 'warning',
            'returned' => 'secondary',
            default => 'gray',
        };
    }

    // ========================================
    // متدهای کمکی
    // ========================================

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isAssigned(): bool
    {
        return $this->status === 'assigned';
    }

    public function markAsReturned(string $reason): void
    {
        $this->update([
            'status' => 'returned',
            'is_returned' => true,
            'return_reason' => $reason,
            'returned_at' => now(),
            'assigned_to_registration_id' => null,
        ]);
    }

    public function makeAvailable(): void
    {
        $this->update([
            'status' => 'available',
            'assigned_to_registration_id' => null,
        ]);
    }
}