<?php

/**
 * آدرس فایل: app/Filament/Widgets/DevicesShortcutWidget.php
 * 
 * دستور ایجاد:
 * php artisan make:filament-widget DevicesShortcutWidget
 */

namespace App\Filament\Widgets;

use App\Models\NewDevice;
use Filament\Widgets\Widget;

class DevicesShortcutWidget extends Widget
{
    protected static string $view = 'filament.widgets.devices-shortcut-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 1;

    /**
     * فقط برای کسانی که دسترسی به دستگاه‌ها دارند نمایش داده شود
     */
    public static function canView(): bool
    {
        $user = auth()->user();
        
        // سوپرادمین و ادمین همیشه می‌بینند
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }
        
        // اپراتورها فقط اگر دسترسی داشته باشند
        return $user->can('view_devices');
    }

    /**
     * داده‌های ویجت
     */
    public function getViewData(): array
    {
        $user = auth()->user();
        
        return [
            'total_devices' => NewDevice::count(),
            'today_devices' => NewDevice::whereDate('created_at', today())->count(),
            'user_devices' => $user->hasRole('operator') 
                ? NewDevice::where('user_id', $user->id)->count() 
                : null,
        ];
    }
}