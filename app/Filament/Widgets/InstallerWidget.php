<?php

/**
 * آدرس فایل: app/Filament/Widgets/InstallerWidget.php
 * 
 * دستور ایجاد:
 * php artisan make:filament-widget InstallerWidget
 */

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InstallerWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'نصاب';
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('در انتظار نصب', Registration::where('installer_id', $user->id)
                ->where('status', 'device_assigned')
                ->count())
                ->description('نیاز به نصب')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('warning')
                ->chart([2, 4, 3, 5, 2, 4, 3]),
            
            Stat::make('نصب شده امروز', Registration::where('installer_id', $user->id)
                ->where('status', 'installed')
                ->whereDate('installation_completed_at', today())
                ->count())
                ->description('توسط شما')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('کل نصب‌های انجام شده', Registration::where('installer_id', $user->id)
                ->where('status', 'installed')
                ->count())
                ->description('مجموع')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('info'),
        ];
    }
}