<?php

/**
 * آدرس فایل: app/Models/NewDevice.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewDevice extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'operator_name',
    ];

    /**
     * رابطه با کاربر (اپراتوری که ثبت کرده)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
