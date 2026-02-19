<?php

namespace App\Filament\Resources\DeviceReportResource\Pages;

use App\Filament\Resources\DeviceReportResource;
use App\Traits\ExportableTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeviceReports extends ListRecords
{
    use ExportableTable;

    protected static string $resource = DeviceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('خروجی Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
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
            'customer' => 'مشتری',
            'province' => 'استان',
            'city' => 'شهرستان',
            'organization' => 'سازمان',
            'creator' => 'ثبت توسط',
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
            'province' => match($record->assignedToRegistration?->province) {
                'fars' => 'فارس', 'bushehr' => 'بوشهر', 'khuzestan' => 'خوزستان',
                'khorasan_razavi' => 'خراسان رضوی', 'zanjan' => 'زنجان', 'hormozgan' => 'هرمزگان',
                'chaharmahal' => 'چهارمحال و بختیاری', 'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                default => $record->assignedToRegistration?->province ?? '—',
            },
            'city' => $record->assignedToRegistration?->city ?? '—',
            'organization' => match($record->assignedToRegistration?->organization) {
                'jihad' => 'جهاد کشاورزی', 'sanat' => 'صنعت معدن و تجارت', 'shilat' => 'سازمان شیلات',
                default => $record->assignedToRegistration?->organization ?? '—',
            },
            'creator' => $record->creator?->name ?? '—',
            'created_at' => $record->created_at?->format('Y/m/d') ?? '—',
            default => $record->{$key} ?? '—',
        };
    }

    public function getExportFileName(): string
    {
        return 'device-reports-' . now()->format('Y-m-d');
    }
}