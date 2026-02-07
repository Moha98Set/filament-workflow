<?php

/**
 * آدرس فایل: app/Filament/Resources/NewDeviceResource/Pages/ListNewDevices.php
 */

namespace App\Filament\Resources\NewDeviceResource\Pages;

use App\Filament\Resources\NewDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewDevices extends ListRecords
{
    protected static string $resource = NewDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('ثبت دستگاه جدید'),
        ];
    }
}
