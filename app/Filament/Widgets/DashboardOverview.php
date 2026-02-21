<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use App\Models\Registration;
use App\Models\User;
use Filament\Widgets\Widget;

class DashboardOverview extends Widget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.dashboard-overview';

    protected function getViewData(): array
    {
        $totalCustomers = Registration::count();
        $installed = Registration::where('status', 'installed')->count();
        $installRate = $totalCustomers > 0 ? round(($installed / $totalCustomers) * 100) : 0;

        return [
            'stats' => [
                'total_customers' => $totalCustomers,
                'pending' => Registration::where('status', 'pending')->count(),
                'financial_approved' => Registration::where('status', 'financial_approved')->count(),
                'device_assigned' => Registration::where('status', 'device_assigned')->count(),
                'ready_for_installation' => Registration::where('status', 'ready_for_installation')->count(),
                'installed' => $installed,
                'relocation_requested' => Registration::where('status', 'relocation_requested')->count(),
                'total_devices' => Device::count(),
                'available_devices' => Device::where('status', 'available')->count(),
                'faulty_devices' => Device::where('status', 'faulty')->count(),
                'active_installers' => User::where('operator_tag', 'نصاب')->where('is_active', true)->count(),
                'install_rate' => $installRate,
            ],
            'recent' => Registration::latest()->limit(5)->get(),
        ];
    }
}