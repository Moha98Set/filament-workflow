<?php

/**
 * آدرس فایل: app/Filament/Resources/NewDeviceResource/Pages/ViewNewDevice.php
 */

namespace App\Filament\Resources\NewDeviceResource\Pages;

use App\Filament\Resources\NewDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNewDevice extends ViewRecord
{
    protected static string $resource = NewDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
