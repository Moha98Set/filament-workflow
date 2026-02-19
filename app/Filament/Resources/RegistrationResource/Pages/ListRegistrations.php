<?php

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use App\Traits\ExportableTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListRegistrations extends ListRecords
{
    use ExportableTable;

    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('خروجی Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
            Actions\CreateAction::make()
                ->label('ثبت‌نام جدید')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getExportColumns(): array
    {
        return [
            'id' => '#',
            'full_name' => 'نام',
            'phone' => 'تلفن',
            'national_id' => 'کد ملی',
            'province' => 'استان',
            'city' => 'شهرستان',
            'organization' => 'سازمان',
            'status' => 'وضعیت',
            'device_serial' => 'سریال دستگاه',
            'created_at' => 'تاریخ ثبت',
        ];
    }

    public function getExportCellValue($record, string $key): string
    {
        return match($key) {
            'province' => match($record->province) {
                'fars' => 'فارس', 'bushehr' => 'بوشهر', 'khuzestan' => 'خوزستان',
                'khorasan_razavi' => 'خراسان رضوی', 'zanjan' => 'زنجان', 'hormozgan' => 'هرمزگان',
                'chaharmahal' => 'چهارمحال و بختیاری', 'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                default => $record->province ?? '—',
            },
            'organization' => match($record->organization) {
                'jihad' => 'جهاد کشاورزی', 'sanat' => 'صنعت معدن و تجارت', 'shilat' => 'سازمان شیلات',
                default => $record->organization ?? '—',
            },
            'status' => $record->status_label ?? $record->status,
            'device_serial' => $record->assignedDevice?->serial_number ?? '—',
            'created_at' => $record->created_at?->format('Y/m/d') ?? '—',
            default => $record->{$key} ?? '—',
        };
    }

    public function getExportFileName(): string
    {
        return 'registrations-' . now()->format('Y-m-d');
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        $tabs = [
            'all' => Tab::make('همه')
                ->badge(fn () => $this->getModel()::count()),
        ];

        if ($user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'کارشناس مالی') {
            $tabs['pending'] = Tab::make('در انتظار بررسی مالی')
                ->icon('heroicon-o-clock')
                ->badge(fn () => $this->getModel()::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'));
        }

        if ($user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'کارشناس فنی') {
            $tabs['financial_approved'] = Tab::make('در انتظار اختصاص دستگاه')
                ->icon('heroicon-o-cpu-chip')
                ->badge(fn () => $this->getModel()::where('status', 'financial_approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'financial_approved'));
        }

        if ($user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'کارشناس فنی') {
            $tabs['device_assigned'] = Tab::make('منتظر آماده‌سازی')
                ->icon('heroicon-o-clipboard-document-check')
                ->badge(fn () => $this->getModel()::where('status', 'device_assigned')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'device_assigned'));
        }

        if ($user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'نصاب') {
            $tabs['ready_for_installation'] = Tab::make('آماده نصب')
                ->icon('heroicon-o-wrench-screwdriver')
                ->badge(fn () => $this->getModel()::where('status', 'ready_for_installation')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'ready_for_installation'));
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            $tabs['relocation_requested'] = Tab::make('درخواست جابجایی')
                ->icon('heroicon-o-arrow-path')
                ->badge(fn () => $this->getModel()::where('status', 'relocation_requested')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'relocation_requested'));
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            $tabs['installed'] = Tab::make('نصب شده')
                ->icon('heroicon-o-check-badge')
                ->badge(fn () => $this->getModel()::where('status', 'installed')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'installed'));
        }

        return $tabs;
    }
}