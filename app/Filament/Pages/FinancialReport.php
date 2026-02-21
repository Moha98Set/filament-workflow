<?php

namespace App\Filament\Pages;

use App\Models\Registration;
use App\Traits\ExportableTable;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class FinancialReport extends Page implements HasTable
{
    use InteractsWithTable;
    use ExportableTable;

    protected static ?string $navigationGroup = 'گزارش‌ها';
    protected static ?string $navigationLabel = 'گزارش مالی';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.financial-report';
    protected static ?string $title = 'گزارش مالی';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']) ||
               auth()->user()->operator_tag === 'کارشناس مالی';
    }

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

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Registration::query()
                    ->whereNotNull('payment_method')
                    ->latest()
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('نام')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable(),

                TextColumn::make('national_id')
                    ->label('کد ملی')
                    ->searchable(),

                TextColumn::make('organization')
                    ->label('سازمان')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
                        default => $state ?? '—',
                    })
                    ->color(fn ($state) => match($state) {
                        'jihad' => 'success', 'sanat' => 'danger', 'shilat' => 'info', default => 'gray',
                    }),

                TextColumn::make('province')
                    ->label('استان')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'fars' => 'فارس', 'bushehr' => 'بوشهر', 'khuzestan' => 'خوزستان',
                        'khorasan_razavi' => 'خراسان رضوی', 'zanjan' => 'زنجان', 'hormozgan' => 'هرمزگان',
                        'chaharmahal' => 'چهارمحال و بختیاری', 'kohgiluyeh' => 'کهگیلویه و بویراحمد',
                        default => $state ?? '—',
                    }),

                TextColumn::make('city')
                    ->label('شهرستان'),

                TextColumn::make('payment_method')
                    ->label('روش پرداخت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'online' => '🏦 آنلاین',
                        'transfer' => '💳 واریز وجه',
                        default => $state ?? '—',
                    })
                    ->color(fn ($state) => match($state) {
                        'online' => 'info',
                        'transfer' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('payment_amount')
                    ->label('مبلغ (تومان)')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 10) : '—')
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('payment_status')
                    ->label('وضعیت پرداخت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'paid' => '✅ پرداخت شده',
                        'pending' => '⏳ در انتظار',
                        'unpaid' => '❌ پرداخت نشده',
                        'failed' => '❌ ناموفق',
                        'unverified' => '⚠️ تایید نشده',
                        default => $state ?? '—',
                    })
                    ->color(fn ($state) => match($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'unpaid', 'failed' => 'danger',
                        'unverified' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('payment_ref_number')
                    ->label('شماره پیگیری')
                    ->default('—')
                    ->copyable(),

                TextColumn::make('payment_verified_at')
                    ->label('تاریخ پرداخت')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->default('—'),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت‌نام')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('روش پرداخت')
                    ->options([
                        'online' => '🏦 آنلاین',
                        'transfer' => '💳 واریز وجه',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('وضعیت پرداخت')
                    ->options([
                        'paid' => '✅ پرداخت شده',
                        'pending' => '⏳ در انتظار',
                        'unpaid' => '❌ پرداخت نشده',
                        'failed' => '❌ ناموفق',
                    ]),

                SelectFilter::make('organization')
                    ->label('سازمان')
                    ->options([
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
                    ]),

                SelectFilter::make('province')
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

                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from')->label('از تاریخ'),
                        DatePicker::make('until')->label('تا تاریخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['until'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    public function getExportColumns(): array
    {
        return [
            'full_name' => 'نام',
            'phone' => 'تلفن',
            'national_id' => 'کد ملی',
            'organization' => 'سازمان',
            'province' => 'استان',
            'city' => 'شهرستان',
            'payment_method' => 'روش پرداخت',
            'payment_amount' => 'مبلغ (تومان)',
            'payment_status' => 'وضعیت پرداخت',
            'payment_ref_number' => 'شماره پیگیری',
            'payment_verified_at' => 'تاریخ پرداخت',
            'created_at' => 'تاریخ ثبت‌نام',
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
            'payment_method' => match($record->payment_method) {
                'online' => 'آنلاین', 'transfer' => 'واریز وجه', default => $record->payment_method ?? '—',
            },
            'payment_amount' => $record->payment_amount ? number_format($record->payment_amount / 10) : '—',
            'payment_status' => match($record->payment_status) {
                'paid' => 'پرداخت شده', 'pending' => 'در انتظار', 'unpaid' => 'پرداخت نشده',
                'failed' => 'ناموفق', 'unverified' => 'تایید نشده', default => $record->payment_status ?? '—',
            },
            'payment_ref_number' => $record->payment_ref_number ?? '—',
            'payment_verified_at' => \App\Helpers\JalaliHelper::toJalaliDateTime($record->payment_verified_at),
            'created_at' => \App\Helpers\JalaliHelper::toJalali($record->created_at),
            default => $record->{$key} ?? '—',
        };
    }

    public function getExportFileName(): string
    {
        return 'financial-report-' . now()->format('Y-m-d');
    }
}