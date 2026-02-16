<?php

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('ثبت‌نام جدید')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();
        
        $tabs = [
            'all' => Tab::make('همه')
                ->badge(fn () => $this->getModel()::count()),
        ];

        // تب‌های مخصوص هر اپراتور
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
            $tabs['installed'] = Tab::make('نصب شده')
                ->icon('heroicon-o-check-badge')
                ->badge(fn () => $this->getModel()::where('status', 'installed')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'installed'));
        }

        return $tabs;
    }
}


