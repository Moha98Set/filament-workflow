<?php

/**
 * آدرس فایل: app/Filament/Widgets/TodayTasksWidget.php
 */

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class TodayTasksWidget extends BaseWidget
{
    // ترتیب نمایش ویجت در داشبورد
    protected static ?int $sort = 1;

    /**
     * فقط برای اپراتورها نمایش داده شود
     */
    public static function canView(): bool
    {
        $user = Auth::user();

        // فقط اپراتورها این ویجت را می‌بینند
        if ($user->hasRole('operator')) {
            return true;
        }

        return false;
    }

    protected function getStats(): array
    {
        // تعداد مشتریان جدید (is_new = 1)
        $newClientsCount = Client::where('is_new', true)->count();

        return [
            Stat::make('کارهای امروز', $newClientsCount . ' مشتری جدید')
                ->description("تعداد {$newClientsCount} مشتری جدید ثبت‌نام کردند و منتظر تایید اطلاعات هستند")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]) // نمودار تزئینی
        ];
    }
}
