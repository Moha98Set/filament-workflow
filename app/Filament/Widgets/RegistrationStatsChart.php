<?php

/**
 * آدرس فایل: app/Filament/Widgets/RegistrationStatsChart.php
 * 
 * دستور ایجاد:
 * php artisan make:filament-widget RegistrationStatsChart --type=chart
 */

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class RegistrationStatsChart extends ChartWidget
{
    protected static ?string $heading = 'آمار ثبت‌نام‌ها (7 روز اخیر)';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('Y/m/d');
            $data[] = Registration::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'تعداد ثبت‌نام',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}