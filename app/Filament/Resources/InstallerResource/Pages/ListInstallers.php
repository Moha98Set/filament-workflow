<?php

namespace App\Filament\Resources\InstallerResource\Pages;

use App\Filament\Resources\InstallerResource;
use App\Traits\ExportableTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstallers extends ListRecords
{
    use ExportableTable;

    protected static string $resource = InstallerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('خروجی Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
            Actions\CreateAction::make()->label('ثبت نصاب جدید'),
        ];
    }

    public function getExportColumns(): array
    {
        return [
            'name' => 'نام',
            'phone' => 'تلفن',
            'national_id' => 'کد ملی',
            'organization' => 'سازمان',
            'province' => 'استان',
            'city' => 'شهرستان',
            'cooperation_start_date' => 'شروع همکاری',
            'is_active' => 'فعال',
        ];
    }

    public function getExportCellValue($record, string $key): string
    {
        return match($key) {
            'organization' => match($record->organization) {
                'jihad' => 'جهاد کشاورزی', 'sanat' => 'صنعت معدن و تجارت', 'shilat' => 'سازمان شیلات',
                default => $record->organization ?? '—',
            },
            'province' => match($record->province) {
                'fars' => 'فارس', 'bushehr' => 'بوشهر', 'khuzestan' => 'خوزستان',
                'khorasan_razavi' => 'خراسان رضوی', 'zanjan' => 'زنجان', 'hormozgan' => 'هرمزگان',
                'chaharmahal' => 'چهارمحال و بختیاری', 'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                default => $record->province ?? '—',
            },
            'cooperation_start_date' => \App\Helpers\JalaliHelper::toJalali($record->cooperation_start_date),
            'is_active' => $record->is_active ? 'فعال' : 'غیرفعال',
            default => $record->{$key} ?? '—',
        };
    }

    public function getExportFileName(): string
    {
        return 'installers-' . now()->format('Y-m-d');
    }
}