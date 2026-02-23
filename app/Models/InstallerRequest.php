<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallerRequest extends Model
{
    protected $fillable = [
        'installer_id', 'registration_id', 'device_id', 'type',
        'description', 'photo', 'status', 'reviewed_by', 'reviewed_at', 'admin_note',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function installer()
    {
        return $this->belongsTo(User::class, 'installer_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'faulty' => '⚠️ گزارش معیوبی',
            'relocation' => '🔄 درخواست جابجایی',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => '⏳ در انتظار بررسی',
            'approved' => '✅ تأیید شده',
            'rejected' => '❌ رد شده',
            default => $this->status,
        };
    }
}