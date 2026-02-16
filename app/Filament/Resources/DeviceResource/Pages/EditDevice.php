<?php
namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDevice extends EditRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
        {
            return [
                Actions\DeleteAction::make()
                    ->before(function () {
                        $registration = \App\Models\Registration::where('assigned_device_id', $this->record->id)->first();
                        if ($registration) {
                            $registration->update([
                                'status' => 'pending',
                                'assigned_device_id' => null,
                                'device_assigned_by' => null,
                                'device_assigned_at' => null,
                            ]);
                        }
                    }),
            ];
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