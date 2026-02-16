<?php 

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [];

        $record = $this->record;
        $user = auth()->user();

        // دکمه بازگشت به مرحله قبل (Rollback)
        if ($user->hasRole(['super_admin', 'admin'])) {
            if ($record->status === 'financial_approved') {
                $actions[] = Actions\Action::make('rollback_to_pending')
                    ->label('بازگشت به انتظار بررسی')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function () use ($record) {
                        $record->update([
                            'status' => 'pending',
                            'financial_approved_by' => null,
                            'financial_approved_at' => null,
                        ]);

                        Notification::make()
                            ->warning()
                            ->title('درخواست به مرحله قبل برگشت')
                            ->send();
                    });
            }

            if ($record->status === 'device_assigned') {
                $actions[] = Actions\Action::make('rollback_to_financial_approved')
                    ->label('بازگشت به تایید مالی')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function () use ($record) {
                        // آزاد کردن دستگاه
                        if ($record->assignedDevice) {
                            $record->assignedDevice->update([
                                'status' => 'available',
                                'assigned_to_registration_id' => null,
                            ]);
                        }

                        $record->update([
                            'status' => 'financial_approved',
                            'device_assigned_by' => null,
                            'device_assigned_at' => null,
                            'assigned_device_id' => null,
                        ]);

                        Notification::make()
                            ->warning()
                            ->title('دستگاه آزاد شد و درخواست برگشت')
                            ->send();
                    });
            }
        }

        $actions[] = Actions\DeleteAction::make()
            ->before(function () {
                if ($this->record->assigned_device_id) {
                    $device = \App\Models\Device::find($this->record->assigned_device_id);
                    if ($device) {
                        $device->update([
                            'status' => 'available',
                            'assigned_to_registration_id' => null,
                        ]);
                    }
                }
            });

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تغییرات ذخیره شد';
    }
}
?>