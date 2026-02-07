<?php

/**
 * آدرس فایل: app/Filament/Widgets/RecentRegistrationsWidget.php
 * 
 * دستور ایجاد:
 * php artisan make:filament-widget RecentRegistrationsWidget --type=table
 */

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentRegistrationsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin', 'operator']);
    }

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->heading('آخرین درخواست‌ها')
            ->query(function () use ($user) {
                $query = Registration::query();

                // فیلتر بر اساس نقش
                if ($user->operator_tag === 'کارشناس مالی') {
                    $query->where('status', 'pending');
                } elseif ($user->operator_tag === 'کارشناس فنی') {
                    $query->where('status', 'financial_approved');
                } elseif ($user->operator_tag === 'نصاب') {
                    $query->where('installer_id', $user->id);
                }

                return $query->latest()->limit(10);
            })
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('نام')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('تلفن')
                    ->icon('heroicon-o-phone'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn ($record) => $record->status_color),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Registration $record): string => route('filament.admin.resources.registrations.edit', ['record' => $record])),
            ]);
    }
}