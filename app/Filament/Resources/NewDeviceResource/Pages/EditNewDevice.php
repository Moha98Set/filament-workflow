<?php

/**
 * آدرس فایل: app/Filament/Resources/NewDeviceResource/Pages/EditNewDevice.php
 */

namespace App\Filament\Resources\NewDeviceResource\Pages;

use App\Filament\Resources\NewDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewDevice extends EditRecord
{
    protected static string $resource = NewDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تغییرات با موفقیت ذخیره شد';
    }
}
