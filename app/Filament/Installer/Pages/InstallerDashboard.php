<?php

namespace App\Filament\Installer\Pages;

use App\Models\Registration;
use App\Models\ActivityLog;
use Filament\Pages\Page;
use App\Models\InstallerRequest;
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
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
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
                        ActivityLog::log('installation_report', "نصب دستگاه برای {$record->full_name} توسط " . auth()->user()->name, $record);
                        //notif
                        $admins = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))->get();
                        foreach ($admins as $admin) {
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('نصب انجام شد')
                                ->body("{$record->full_name} توسط " . auth()->user()->name)
                                ->icon('heroicon-o-check-badge')
                                ->sendToDatabase($admin);
                        }
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
                                    ->default(fn ($record) => "👤 {$record?->full_name} | 📱 {$record?->assignedDevice?->serial_number} | 📞 {$record?->phone}"),

                                Forms\Components\Select::make('relocation_type')
                                    ->label('نوع جابجایی')
                                    ->options([
                                        'swap' => '🔄 جابجایی متقابل (تعویض دو دستگاه)',
                                        'transfer' => '➡️ انتقال دستگاه به متقاضی بدون دستگاه',
                                    ])
                                    ->required()
                                    ->live()
                                    ->native(false),

                                // جابجایی متقابل — فقط متقاضیانی که دستگاه دارن
                                Forms\Components\Select::make('to_registration_id')
                                    ->label('متقاضی مقصد (دارای دستگاه)')
                                    ->required()
                                    ->searchable()
                                    ->options(function ($record) {
                                        return Registration::where('installer_id', auth()->id())
                                            ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                                            ->where('id', '!=', $record->id)
                                            ->whereNotNull('assigned_device_id')
                                            ->get()
                                            ->mapWithKeys(fn ($reg) => [
                                                $reg->id => "👤 {$reg->full_name} | 📱 {$reg->assignedDevice?->serial_number} | 📞 {$reg->phone}"
                                            ]);
                                    })
                                    ->helperText('دستگاه این دو متقاضی با هم عوض می‌شود')
                                    ->native(false)
                                    ->visible(fn (Forms\Get $get) => $get('relocation_type') === 'swap'),

                                // انتقال — فقط متقاضیانی که دستگاه ندارن
                                Forms\Components\Select::make('to_registration_id')
                                    ->label('متقاضی مقصد (بدون دستگاه)')
                                    ->required()
                                    ->searchable()
                                    ->options(function ($record) {
                                        return Registration::where('installer_id', auth()->id())
                                            ->where('status', 'financial_approved')
                                            ->whereNull('assigned_device_id')
                                            ->get()
                                            ->mapWithKeys(fn ($reg) => [
                                                $reg->id => "👤 {$reg->full_name} | 🚫 بدون دستگاه | 📞 {$reg->phone}"
                                            ]);
                                    })
                                    ->helperText('دستگاه از متقاضی مبدأ جدا شده و به این متقاضی منتقل می‌شود')
                                    ->native(false)
                                    ->visible(fn (Forms\Get $get) => $get('relocation_type') === 'transfer'),

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
                        // فقط درخواست ثبت میشه — تغییری در وضعیت نمیشه
                        InstallerRequest::create([
                            'installer_id' => auth()->id(),
                            'registration_id' => $record->id,
                            'device_id' => $record->assigned_device_id,
                            'type' => $data['failure_type'] === 'device_faulty' ? 'faulty' : 'relocation',
                            'description' => $data['failure_type'] === 'device_faulty'
                                ? ($data['fault_reason'] ?? '')
                                : "نوع: " . ($data['relocation_type'] === 'swap' ? 'جابجایی متقابل' : 'انتقال')
                                  . " | مقصد: " . (Registration::find($data['swap_to_registration_id'] ?? $data['transfer_to_registration_id'] ?? null)?->full_name ?? '—')
                                  . " | دلیل: " . ($data['relocation_reason'] ?? ''),
                            'photo' => $data['fault_photo'] ?? null,
                            'status' => 'pending',
                        ]);

                        $typeLabel = $data['failure_type'] === 'device_faulty' ? 'معیوبی' : 'جابجایی';

                        Notification::make()
                            ->warning()
                            ->title("درخواست {$typeLabel} ثبت شد")
                            ->body('لطفاً منتظر بررسی و تأیید کارشناسان باشید')
                            ->send();

                        ActivityLog::log(
                            $data['failure_type'] === 'device_faulty' ? 'device_faulty' : 'relocation_requested',
                            "درخواست {$typeLabel} توسط نصاب " . auth()->user()->name . " — مشتری: {$record->full_name}",
                            $record
                        );

                        // نوتیفیکیشن به ادمین
                        $admins = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))->get();
                        foreach ($admins as $admin) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title("درخواست جدید: {$typeLabel}")
                                ->body("{$record->full_name} — نصاب: " . auth()->user()->name)
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($admin);
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