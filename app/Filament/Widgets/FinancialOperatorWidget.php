<?php

/**
 * آدرس فایل: app/Filament/Widgets/FinancialOperatorWidget.php
 * 
 * دستور ایجاد:
 * php artisan make:filament-widget FinancialOperatorWidget
 */

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialOperatorWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasRole(['super_admin', 'admin']) || $user->operator_tag === 'کارشناس مالی';
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('در انتظار بررسی مالی', Registration::where('status', 'pending')->count())
                ->description('نیاز به بررسی')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            
            Stat::make('تایید شده امروز', Registration::where('status', 'financial_approved')
                ->where('financial_approved_by', $user->id)
                ->whereDate('financial_approved_at', today())
                ->count())
                ->description('توسط شما')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('رد شده', Registration::where('status', 'financial_rejected')
                ->where('financial_approved_by', $user->id)
                ->count())
                ->description('کل موارد رد شده')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}