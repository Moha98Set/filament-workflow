<?php

namespace App\Filament\Resources\DeviceReportResource\Pages;

use App\Filament\Resources\DeviceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeviceReport extends EditRecord
{
    protected static string $resource = DeviceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
