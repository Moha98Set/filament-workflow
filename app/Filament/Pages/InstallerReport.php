<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Registration;
use App\Traits\ExportableTable;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;

class InstallerReport extends Page implements HasTable
{
    use InteractsWithTable;
    use ExportableTable;

    protected static ?string $navigationGroup = 'گزارش‌ها';
    protected static ?string $navigationLabel = 'نصاب‌ها';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.installer-report';

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export')
                ->label('خروجی Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
        ];
    }

    public function getExportColumns(): array
    {
        return [
            'name' => 'نام',
            'phone' => 'تلفن',
            'national_id' => 'کد ملی',
            'organization' => 'سازمان',
            'province' => 'استان',
            'city' => 'شهرستان',
            'cooperation_start_date' => 'شروع همکاری',
            'is_active' => 'فعال',
        ];
    }

    public function getExportCellValue($record, string $key): string
    {
        return match($key) {
            'organization' => match($record->organization) {
                'jihad' => 'جهاد کشاورزی', 'sanat' => 'صنعت معدن و تجارت', 'shilat' => 'سازمان شیلات',
                default => $record->organization ?? '—',
            },
            'province' => match($record->province) {
                'fars' => 'فارس', 'bushehr' => 'بوشهر', 'khuzestan' => 'خوزستان',
                'khorasan_razavi' => 'خراسان رضوی', 'zanjan' => 'زنجان', 'hormozgan' => 'هرمزگان',
                'chaharmahal' => 'چهارمحال و بختیاری', 'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                default => $record->province ?? '—',
            },
            'cooperation_start_date' => $record->cooperation_start_date?->format('Y/m/d') ?? '—',
            'is_active' => $record->is_active ? 'فعال' : 'غیرفعال',
            default => $record->{$key} ?? '—',
        };
    }

    public function getExportFileName(): string
    {
        return 'installers-report-' . now()->format('Y-m-d');
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('operator_tag', 'نصاب')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('نام نصاب')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                TextColumn::make('organization')
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
                    })
                    ->sortable(),

                TextColumn::make('province')
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

                TextColumn::make('city')
                    ->label('شهرستان')
                    ->searchable(),

                TextColumn::make('cooperation_start_date')
                    ->label('شروع همکاری')
                    ->date('Y/m/d')
                    ->sortable(),

                TextColumn::make('total_assigned')
                    ->label('کل پذیرفته')
                    ->getStateUsing(fn (User $record) =>
                        Registration::where('installer_id', $record->id)->count()
                    )
                    ->badge()
                    ->color('primary'),

                TextColumn::make('installed_count')
                    ->label('نصب شده')
                    ->getStateUsing(fn (User $record) =>
                        Registration::where('installer_id', $record->id)
                            ->where('status', 'installed')
                            ->count()
                    )
                    ->badge()
                    ->color('success'),

                TextColumn::make('pending_count')
                    ->label('نصب نشده')
                    ->getStateUsing(fn (User $record) =>
                        Registration::where('installer_id', $record->id)
                            ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                            ->count()
                    )
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organization')
                    ->label('سازمان')
                    ->options([
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
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
                    ]),

                Tables\Filters\SelectFilter::make('city')
                    ->label('شهرستان')
                    ->searchable()
                    ->options(function () {
                        return User::where('operator_tag', 'نصاب')
                            ->whereNotNull('city')
                            ->distinct()
                            ->pluck('city', 'city')
                            ->toArray();
                    }),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('فعال')
                    ->falseLabel('غیرفعال'),
            ])
            ->actions([
                Tables\Actions\Action::make('list_installed')
                    ->label('نصب شده‌ها')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->modalHeading(fn (User $record) => 'دستگاه‌های نصب شده - ' . $record->name)
                    ->modalContent(function (User $record) {
                        $registrations = \App\Models\Registration::where('installer_id', $record->id)
                            ->where('status', 'installed')
                            ->with('assignedDevice')
                            ->get();

                        if ($registrations->isEmpty()) {
                            return view('filament.components.empty-list', ['message' => 'هیچ نصب انجام‌شده‌ای وجود ندارد']);
                        }

                        return view('filament.components.device-list', [
                            'registrations' => $registrations,
                            'type' => 'installed',
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن'),

                Tables\Actions\Action::make('list_pending')
                    ->label('نصب نشده‌ها')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->modalHeading(fn (User $record) => 'دستگاه‌های در انتظار نصب - ' . $record->name)
                    ->modalContent(function (User $record) {
                        $registrations = \App\Models\Registration::where('installer_id', $record->id)
                            ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                            ->with('assignedDevice')
                            ->get();

                        if ($registrations->isEmpty()) {
                            return view('filament.components.empty-list', ['message' => 'هیچ دستگاه در انتظار نصبی وجود ندارد']);
                        }

                        return view('filament.components.device-list', [
                            'registrations' => $registrations,
                            'type' => 'pending',
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('هیچ نصابی ثبت نشده')
            ->emptyStateIcon('heroicon-o-users');
    }
}