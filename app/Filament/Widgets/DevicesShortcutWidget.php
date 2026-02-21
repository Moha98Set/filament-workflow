<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DevicesShortcutWidget extends Widget
{
    protected static bool $isDiscovered = false;
    protected static string $view = 'filament.widgets.dashboard-overview';
    
    public static function canView(): bool
    {
        return false;
    }
}