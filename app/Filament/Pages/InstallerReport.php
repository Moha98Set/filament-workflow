<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class InstallerReport extends Page
{
    protected static ?string $navigationGroup = 'گزارش‌ها';

    protected static ?string $navigationLabel = 'نصاب‌ها';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.installer-report';
}
