<?php

/**
 * آدرس فایل: app/Filament/Widgets/NewClientsStatsWidget.php
 */

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class NewClientsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    /**
     * فقط برای اپراتورها نمایش داده شود
     */
    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->hasRole('operator');
    }

    protected function getStats(): array
    {
        // تعداد مشتریان جدید منتظر تایید
        $newClientsCount = Client::where('is_new', true)->count();

        // تعداد مشتریان امروز
        $todayClientsCount = Client::whereDate('created_at', today())->count();

        // تعداد کل مشتریان
        $totalClientsCount = Client::count();

        return [
            // کارت اصلی - مشتریان منتظر تایید
            Stat::make('کارهای امروز', $newClientsCount . ' مشتری جدید')
                ->description("تعداد {$newClientsCount} مشتری جدید ثبت‌نام کردند و منتظر تایید اطلاعات هستند")
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-success-500',
                ]),

            // کارت دوم - مشتریان امروز
            Stat::make('ثبت‌نام امروز', $todayClientsCount)
                ->description('مشتریان ثبت‌نام شده امروز')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            // کارت سوم - کل مشتریان
            Stat::make('کل مشتریان', $totalClientsCount)
                ->description('تعداد کل مشتریان ثبت شده')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
