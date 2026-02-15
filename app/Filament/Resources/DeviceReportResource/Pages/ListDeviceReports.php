<?php

namespace App\Filament\Resources\DeviceReportResource\Pages;

use App\Filament\Resources\DeviceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeviceReports extends ListRecords
{
    protected static string $resource = DeviceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
