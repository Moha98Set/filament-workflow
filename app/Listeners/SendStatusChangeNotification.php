<?php

/**
 * آدرس فایل: app/Listeners/SendStatusChangeNotification.php
 * 
 * دستور ایجاد:
 * php artisan make:listener SendStatusChangeNotification --event=RegistrationStatusChanged
 */

namespace App\Listeners;

use App\Events\RegistrationStatusChanged;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class SendStatusChangeNotification
{
    public function handle(RegistrationStatusChanged $event): void
    {
        $registration = $event->registration;
        $newStatus = $event->newStatus;

        // ارسال نوتیفیکیشن بر اساس وضعیت جدید
        match($newStatus) {
            'financial_approved' => $this->notifyTechnicalExperts($registration),
            'device_assigned' => $this->notifyInstallers($registration),
            'installed' => $this->notifyAdmins($registration),
            default => null,
        };
    }

    protected function notifyTechnicalExperts($registration): void
    {
        $experts = User::where('operator_tag', 'کارشناس فنی')
            ->where('status', 'active')
            ->get();

        foreach ($experts as $expert) {
            Notification::make()
                ->success()
                ->title('درخواست جدید آماده اختصاص دستگاه')
                ->body("درخواست {$registration->full_name} تایید مالی شد و آماده اختصاص دستگاه است")
                ->icon('heroicon-o-cpu-chip')
                ->actions([
                    Action::make('view')
                        ->label('مشاهده')
                        ->url(route('filament.admin.resources.registrations.edit', ['record' => $registration]))
                        ->button(),
                ])
                ->sendToDatabase($expert);
        }
    }

    protected function notifyInstallers($registration): void
    {
        $installers = User::where('operator_tag', 'نصاب')
            ->where('status', 'active')
            ->get();

        foreach ($installers as $installer) {
            Notification::make()
                ->info()
                ->title('دستگاه جدید آماده نصب')
                ->body("دستگاه {$registration->assignedDevice->code} برای {$registration->full_name} آماده نصب است")
                ->icon('heroicon-o-wrench-screwdriver')
                ->actions([
                    Action::make('view')
                        ->label('مشاهده')
                        ->url(route('filament.admin.resources.registrations.edit', ['record' => $registration]))
                        ->button(),
                ])
                ->sendToDatabase($installer);
        }
    }

    protected function notifyAdmins($registration): void
    {
        $admins = User::role(['super_admin', 'admin'])->get();

        foreach ($admins as $admin) {
            Notification::make()
                ->success()
                ->title('نصب موفقیت‌آمیز')
                ->body("دستگاه {$registration->assignedDevice->code} برای {$registration->full_name} با موفقیت نصب شد")
                ->icon('heroicon-o-check-badge')
                ->sendToDatabase($admin);
        }
    }
}
?>