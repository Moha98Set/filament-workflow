<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'operator_tag',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Scope برای کاربران منتظر تایید
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope برای کاربران فعال
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope برای کاربران رد شده
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * رابطه با کاربری که تایید کرده
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Accessor برای نمایش فارسی وضعیت
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'منتظر تایید',
            'active' => 'فعال',
            'rejected' => 'رد شده',
            'suspended' => 'معلق',
            default => 'نامشخص',
        };
    }

    /**
     * بررسی فعال بودن کاربر
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * بررسی منتظر تایید بودن
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Override متد Authenticatable برای جلوگیری از ورود کاربران غیرفعال
     */
    public function canLogin(): bool
    {
        return $this->status === 'active';
    }
}
