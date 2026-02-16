<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceReportResource\Pages;
use App\Models\Device;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DeviceReportResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'گزارش‌ها';

    protected static ?string $navigationLabel = 'دستگاه‌ها';

    protected static ?string $modelLabel = 'گزارش دستگاه';

    protected static ?string $pluralModelLabel = 'گزارش دستگاه‌ها';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'device-reports';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial_number')
                    ->label('سریال دستگاه')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'موجود',
                        'assigned' => 'اختصاص داده شده',
                        'installed' => 'نصب شده',
                        'faulty' => 'معیوب',
                        'maintenance' => 'در تعمیر',
                        'returned' => 'مرجوع شده',
                        default => $state,
                    })
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['available', 'installed']),
                        'info' => 'assigned',
                        'danger' => 'faulty',
                        'warning' => 'maintenance',
                        'secondary' => 'returned',
                    ]),

                TextColumn::make('assignedToRegistration.full_name')
                    ->label('مشتری')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('assignedToRegistration.province')
                    ->label('استان')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'fars' => 'فارس',
                        'bushehr' => 'بوشهر',
                        'khuzestan' => 'خوزستان',
                        'khorasan_razavi' => 'خراسان رضوی',
                        'zanjan' => 'زنجان',
                        'hormozgan' => 'هرمزگان',
                        'chaharmahal' => 'چهارمحال و بختیاری',
                        'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                        default => $state ?? '—',
                    })
                    ->sortable(),

                TextColumn::make('assignedToRegistration.organization')
                    ->label('سازمان')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
                        default => $state ?? '—',
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'jihad' => 'success',
                        'sanat' => 'danger',
                        'shilat' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('assignedToRegistration.city')
                    ->label('شهرستان')
                    ->default('—'),

                Tables\Columns\IconColumn::make('has_sim')
                    ->label('سیمکارت')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('sim_number')
                    ->label('شماره سیم')
                    ->default('—')
                    ->toggleable(),

                TextColumn::make('creator.name')
                    ->label('ثبت توسط')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('آخرین تغییر')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->multiple()
                    ->options([
                        'available' => 'موجود',
                        'assigned' => 'اختصاص داده شده',
                        'installed' => 'نصب شده',
                        'faulty' => 'معیوب',
                        'maintenance' => 'در تعمیر',
                        'returned' => 'مرجوع شده',
                    ]),

                Tables\Filters\SelectFilter::make('province')
                    ->label('استان')
                    ->options([
                        'fars' => 'فارس',
                        'bushehr' => 'بوشهر',
                        'khuzestan' => 'خوزستان',
                        'khorasan_razavi' => 'خراسان رضوی',
                        'zanjan' => 'زنجان',
                        'hormozgan' => 'هرمزگان',
                        'chaharmahal' => 'چهارمحال و بختیاری',
                        'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('assignedToRegistration', fn ($q) => $q->where('province', $data['value']));
                        }
                    }),

                Tables\Filters\SelectFilter::make('organization')
                    ->label('سازمان')
                    ->options([
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('assignedToRegistration', fn ($q) => $q->where('organization', $data['value']));
                        }
                    }),

                Tables\Filters\SelectFilter::make('city')
                    ->label('شهرستان')
                    ->searchable()
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('assignedToRegistration', fn ($q) => $q->where('city', $data['value']));
                        }
                    })
                    ->options(function () {
                        return \App\Models\Registration::whereNotNull('city')
                            ->distinct()
                            ->pluck('city', 'city')
                            ->toArray();
                    }),

                Tables\Filters\TernaryFilter::make('has_sim')
                    ->label('سیمکارت')
                    ->placeholder('همه')
                    ->trueLabel('دارای سیم')
                    ->falseLabel('بدون سیم'),

                Tables\Filters\SelectFilter::make('created_by')
                    ->label('ثبت توسط')
                    ->options(function () {
                        return User::whereHas('createdDevices')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            $query->where('created_by', $data['value']);
                        }
                    }),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('هیچ دستگاهی ثبت نشده')
            ->emptyStateIcon('heroicon-o-document-chart-bar');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeviceReports::route('/'),
        ];
    }
}