<?php

/**
 * آدرس فایل: app/Filament/Widgets/OperatorDashboardWidget.php
 */

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class OperatorDashboardWidget extends Widget
{
    protected static string $view = 'filament.widgets.operator-dashboard-widget';

    protected static ?int $sort = 1;

    /**
     * فقط برای اپراتورها نمایش داده شود
     */
    public static function canView(): bool
    {
        $user = Auth::user();

        // فقط اپراتورها این ویجت را می‌بینند
        if ($user && $user->hasRole('operator')) {
            return true;
        }

        return false;
    }

    protected function getViewData(): array
    {
        // تعداد مشتریان جدید
        $newClientsCount = Client::where('is_new', true)->count();

        // تعداد مشتریان امروز
        $todayClientsCount = Client::whereDate('created_at', today())->count();

        return [
            'newClientsCount' => $newClientsCount,
            'todayClientsCount' => $todayClientsCount,
        ];
    }
}
