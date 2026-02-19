<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use App\Traits\ExportableTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDevices extends ListRecords
{
    use ExportableTable;

    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('خروجی Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
            Actions\CreateAction::make()
                ->label('ثبت دستگاه جدید')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getExportColumns(): array
    {
        return [
            'serial_number' => 'سریال دستگاه',
            'type' => 'نوع',
            'status' => 'وضعیت',
            'has_sim' => 'سیمکارت',
            'sim_number' => 'شماره سیم',
            'sim_serial' => 'سریال سیم',
            'customer' => 'مشتری',
            'created_at' => 'تاریخ ثبت',
        ];
    }

    public function getExportCellValue($record, string $key): string
    {
        return match($key) {
            'status' => match($record->status) {
                'available' => 'موجود', 'assigned' => 'اختصاص داده شده',
                'installed' => 'نصب شده', 'faulty' => 'معیوب',
                'maintenance' => 'در تعمیر', 'returned' => 'مرجوع شده',
                default => $record->status ?? '—',
            },
            'has_sim' => $record->has_sim ? 'دارد' : 'ندارد',
            'customer' => $record->assignedToRegistration?->full_name ?? '—',
            'created_at' => $record->created_at?->format('Y/m/d') ?? '—',
            default => $record->{$key} ?? '—',
        };
    }

    public function getExportFileName(): string
    {
        return 'devices-' . now()->format('Y-m-d');
    }
}