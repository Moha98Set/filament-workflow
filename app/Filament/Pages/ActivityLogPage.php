<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class ActivityLogPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'گزارش‌ها';
    protected static ?string $navigationLabel = 'لاگ فعالیت‌ها';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.activity-log';
    protected static ?string $title = 'لاگ فعالیت‌ها';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLog::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('کاربر')
                    ->weight('bold')
                    ->default('سیستم')
                    ->searchable(),

                Tables\Columns\TextColumn::make('action_label')
                    ->label('عملیات')
                    ->badge()
                    ->color(fn (ActivityLog $record) => match($record->action) {
                        'financial_approved', 'preparation_approved', 'installation_report' => 'success',
                        'financial_rejected', 'device_faulty', 'installation_failed' => 'danger',
                        'device_transfer', 'relocation_requested', 'status_change' => 'warning',
                        'auto_assign', 'device_assigned', 'installer_assigned' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('توضیحات')
                    ->limit(60)
                    ->tooltip(fn (ActivityLog $record) => $record->description)
                    ->searchable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('نوع عملیات')
                    ->multiple()
                    ->options([
                        'financial_approved' => '✅ تأیید مالی',
                        'financial_rejected' => '❌ رد مالی',
                        'device_assigned' => '📱 اختصاص دستگاه',
                        'preparation_approved' => '✅ تأیید آماده‌سازی',
                        'installer_assigned' => '🔧 انتقال به نصاب',
                        'installation_report' => '🔧 گزارش نصب',
                        'installation_failed' => '❌ عدم نصب',
                        'device_faulty' => '⚠️ دستگاه معیوب',
                        'device_transfer' => '🔀 جابجایی',
                        'relocation_requested' => '🔄 درخواست جابجایی',
                        'auto_assign' => '⚡ اختصاص خودکار',
                        'registration_created' => '📝 ثبت‌نام',
                        'device_created' => '📦 ثبت دستگاه',
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('کاربر')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}