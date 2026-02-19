<?php

namespace App\Filament\Installer\Pages;

use App\Models\Registration;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Filament\Notifications\Notification;

class InstallerDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'داشبورد';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.installer.pages.dashboard';
    protected static ?string $title = 'داشبورد نصاب';

    public string $activeTab = 'pending';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Registration::query()
                    ->where('installer_id', auth()->id())
                    ->when($this->activeTab === 'pending', fn ($q) => $q->whereIn('status', ['device_assigned', 'ready_for_installation']))
                    ->when($this->activeTab === 'installed', fn ($q) => $q->where('status', 'installed'))
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('نام مشتری')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                TextColumn::make('city')
                    ->label('شهرستان')
                    ->searchable(),

                TextColumn::make('installation_address')
                    ->label('آدرس نصب')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->installation_address),

                TextColumn::make('assignedDevice.serial_number')
                    ->label('سریال دستگاه')
                    ->badge()
                    ->color('info'),

                TextColumn::make('assignedDevice.sim_number')
                    ->label('شماره سیمکارت')
                    ->default('—'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'device_assigned' => 'منتظر آماده‌سازی',
                        'ready_for_installation' => 'آماده نصب',
                        'installed' => 'نصب شده',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'device_assigned',
                        'info' => 'ready_for_installation',
                        'success' => 'installed',
                    ]),

                TextColumn::make('installation_completed_at')
                    ->label('تاریخ نصب')
                    ->dateTime('Y/m/d H:i')
                    ->placeholder('—')
                    ->visible(fn () => $this->activeTab === 'installed'),
            ])
            ->actions([
                Tables\Actions\Action::make('report_installation')
                    ->label('گزارش نصب')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Registration $record) => in_array($record->status, ['device_assigned', 'ready_for_installation']))
                    ->form([
                        Forms\Components\FileUpload::make('installation_photo')
                            ->label('عکس دستگاه نصب‌شده')
                            ->image()
                            ->required()
                            ->maxSize(5120)
                            ->directory('installation-photos')
                            ->helperText('عکس از دستگاه نصب‌شده روی تراکتور بگیرید (حداکثر ۵ مگابایت)'),

                        Forms\Components\Textarea::make('installation_note')
                            ->label('یادداشت نصب')
                            ->rows(3)
                            ->placeholder('توضیحات درباره نصب...'),
                    ])
                    ->modalHeading('گزارش نصب دستگاه')
                    ->modalSubmitActionLabel('تأیید نصب')
                    ->action(function (Registration $record, array $data) {
                        $record->update([
                            'status' => 'installed',
                            'installation_completed_at' => now(),
                            'installation_note' => $data['installation_note'] ?? null,
                            'installation_photos' => $data['installation_photo'] ?? null,
                        ]);

                        if ($record->assignedDevice) {
                            $record->assignedDevice->update([
                                'status' => 'installed',
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('گزارش نصب ثبت شد')
                            ->body("نصب دستگاه برای {$record->full_name} ثبت شد")
                            ->send();
                    }),

                Tables\Actions\Action::make('report_failed')
                    ->label('عدم نصب')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Registration $record) => in_array($record->status, ['device_assigned', 'ready_for_installation']))
                    ->form([
                        Forms\Components\Select::make('failure_type')
                            ->label('نوع مشکل')
                            ->required()
                            ->options([
                                'device_faulty' => '🔧 خرابی دستگاه',
                                'relocation_request' => '🔄 درخواست جابجایی',
                            ])
                            ->live()
                            ->native(false),

                        // فیلدهای خرابی دستگاه
                        Forms\Components\Section::make('جزئیات خرابی')
                            ->schema([
                                Forms\Components\Textarea::make('fault_reason')
                                    ->label('توضیحات خرابی')
                                    ->required()
                                    ->rows(3)
                                    ->placeholder('مشکل دستگاه را توضیح دهید...'),

                                Forms\Components\FileUpload::make('fault_photo')
                                    ->label('عکس دستگاه معیوب')
                                    ->image()
                                    ->maxSize(5120)
                                    ->directory('failure-photos'),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('failure_type') === 'device_faulty'),

                        // فیلدهای جابجایی
                        Forms\Components\Section::make('جابجایی دستگاه')
                            ->schema([
                                Forms\Components\TextInput::make('from_customer')
                                    ->label('از متقاضی')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->default(fn ($record) => $record?->full_name . ' | ' . $record?->phone),

                                Forms\Components\Select::make('to_registration_id')
                                    ->label('به متقاضی')
                                    ->required()
                                    ->searchable()
                                    ->options(function ($record) {
                                        $installer = auth()->user();

                                        // متقاضی‌های هم‌استان و هم‌شهرستان که نصب نشده
                                        $pending = Registration::where('installer_id', auth()->id())
                                            ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                                            ->where('id', '!=', $record->id)
                                            ->get()
                                            ->mapWithKeys(fn ($reg) => [
                                                $reg->id => "👤 {$reg->full_name} | 📱 " . ($reg->assignedDevice?->serial_number ?? '—') . " | 📞 {$reg->phone}"
                                            ]);

                                        // متقاضی‌های هم‌استان و هم‌شهرستان که دستگاه معیوب دارن
                                        $faulty = Registration::whereIn('status', ['device_assigned', 'ready_for_installation'])
                                            ->where('id', '!=', $record->id)
                                            ->whereNotNull('assigned_device_id')
                                            ->whereHas('assignedDevice', fn ($q) => $q->where('status', 'faulty'))
                                            ->when($installer->province, fn ($q) => $q->where('province', $installer->province))
                                            ->when($installer->city, fn ($q) => $q->where('city', $installer->city))
                                            ->get()
                                            ->mapWithKeys(fn ($reg) => [
                                                $reg->id => "🔧 {$reg->full_name} | 📱 " . ($reg->assignedDevice?->serial_number ?? '—') . " (معیوب) | 📞 {$reg->phone}"
                                            ]);

                                        return $pending->merge($faulty);
                                    })
                                    ->helperText('متقاضیان نصب‌نشده و متقاضیان با دستگاه معیوب (هم‌استان و هم‌شهرستان)')
                                    ->native(false),

                                Forms\Components\Textarea::make('relocation_reason')
                                    ->label('دلیل جابجایی')
                                    ->required()
                                    ->rows(3)
                                    ->placeholder('دلیل درخواست جابجایی را توضیح دهید...'),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('failure_type') === 'relocation_request'),
                    ])
                    ->modalHeading('گزارش عدم نصب')
                    ->modalSubmitActionLabel('ثبت گزارش')
                    ->modalWidth('lg')
                    ->action(function (Registration $record, array $data) {
                        if ($data['failure_type'] === 'device_faulty') {
                            $device = $record->assignedDevice;

                            if ($device) {
                                $device->update([
                                    'status' => 'faulty',
                                    'notes' => 'گزارش نصاب: ' . $data['fault_reason'],
                                    'assigned_to_registration_id' => null,
                                ]);
                            }

                            $record->update([
                                'status' => 'financial_approved',
                                'assigned_device_id' => null,
                                'device_assigned_by' => null,
                                'device_assigned_at' => null,
                                'installer_id' => null,
                                'sim_activated' => false,
                                'device_tested' => false,
                                'preparation_approved_by' => null,
                                'preparation_approved_at' => null,
                                'installation_note' => 'خرابی دستگاه: ' . $data['fault_reason'],
                            ]);

                            Notification::make()
                                ->danger()
                                ->title('دستگاه معیوب گزارش شد')
                                ->body("دستگاه از {$record->full_name} جدا شد و مشتری به انتظار اختصاص دستگاه برگشت")
                                ->send();

                        } elseif ($data['failure_type'] === 'relocation_request') {
                            $record->update([
                                'status' => 'relocation_requested',
                                'installation_note' => 'درخواست جابجایی: ' . $data['relocation_reason'],
                            ]);

                            Notification::make()
                                ->warning()
                                ->title('درخواست جابجایی ثبت شد')
                                ->body("درخواست جابجایی برای {$record->full_name} به ادمین ارسال شد. منتظر تأیید باشید.")
                                ->send();
                        }
                    }),
            ])
            ->emptyStateHeading($this->activeTab === 'pending' ? 'دستگاهی برای نصب ندارید' : 'هنوز نصبی انجام نشده')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->defaultSort('created_at', 'desc');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }
}