<?php

/**
 * آدرس فایل: app/Observers/RegistrationObserver.php
 * 
 * دستور ایجاد:
 * php artisan make:observer RegistrationObserver --model=Registration
 */

namespace App\Observers;

use App\Models\Registration;
use App\Events\RegistrationStatusChanged;

class RegistrationObserver
{
    public function updating(Registration $registration): void
    {
        // اگر وضعیت تغییر کرده باشد
        if ($registration->isDirty('status')) {
            $oldStatus = $registration->getOriginal('status');
            $newStatus = $registration->status;

            // فایر کردن Event
            event(new RegistrationStatusChanged($registration, $oldStatus, $newStatus));
        }
    }
}

?>