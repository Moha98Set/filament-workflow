<?php

/**
 * آدرس فایل: app/Filament/Widgets/TechnicalOperatorWidget.php
 * 
 * دستور ایجاد:
 * php artisan make:filament-widget TechnicalOperatorWidget
 */

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\Device;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TechnicalOperatorWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'کارشناس فنی';
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('آماده اختصاص دستگاه', Registration::where('status', 'financial_approved')->count())
                ->description('نیاز به اختصاص دستگاه')
                ->descriptionIcon('heroicon-o-cpu-chip')
                ->color('info')
                ->chart([3, 5, 4, 6, 3, 5, 4]),
            
            Stat::make('دستگاه‌های موجود', Device::where('status', 'available')->count())
                ->description('آماده اختصاص')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('اختصاص داده شده امروز', Registration::where('status', 'device_assigned')
                ->where('device_assigned_by', $user->id)
                ->whereDate('device_assigned_at', today())
                ->count())
                ->description('توسط شما')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}