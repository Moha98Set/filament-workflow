<?php

/**
 * آدرس فایل: app/Events/RegistrationStatusChanged.php
 * 
 * دستور ایجاد:
 * php artisan make:event RegistrationStatusChanged
 */

namespace App\Events;

use App\Models\Registration;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegistrationStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Registration $registration,
        public string $oldStatus,
        public string $newStatus
    ) {}
}



