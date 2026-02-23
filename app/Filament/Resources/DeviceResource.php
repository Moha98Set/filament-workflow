<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages;
use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    
    protected static ?string $navigationLabel = 'مدیریت دستگاه‌ها';
    
    protected static ?string $navigationGroup = 'مدیریت';
    
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }
        
        return $user->can('view_devices') || $user->operator_tag === 'کارشناس فنی';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // فرم Create - Textarea ساده
                Forms\Components\Textarea::make('serial_numbers')
                    ->label('سریال دستگاه‌ها')
                    ->placeholder("هر سریال را در یک خط جدید وارد کنید (Enter بزنید):\n\nFCCC\nFGGG\nFHHH\nFKKK\nFLLL")
                    ->rows(25)
                    ->required()
                    ->helperText('💡 برای اضافه کردن سریال جدید، Enter بزنید و سریال بعدی را وارد کنید')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'font-mono text-lg',
                        'style' => 'min-height: 600px !important; resize: vertical;'
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateDevice),
            
                Forms\Components\Select::make('type')
                    ->label('نوع دستگاه (برای همه سریال‌ها)')
                    ->options([
                        'GPS Tracker' => 'GPS Tracker',
                        'Fleet Management' => 'Fleet Management',
                        'Temperature Sensor' => 'Temperature Sensor',
                        'Fuel Monitor' => 'Fuel Monitor',
                        'Speed Limiter' => 'Speed Limiter',
                    ])
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->helperText('این نوع برای تمام سریال‌های وارد شده اعمال می‌شود')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateDevice),
                    
                // ← اینجا اضافه کن
                Forms\Components\Section::make('آپلود فایل اکسل')
                    ->description('فایل اکسل با ۳ ستون: کد دستگاه، شماره سیمکارت، سریال سیمکارت')
                    ->schema([
                        Forms\Components\FileUpload::make('excel_file')
                            ->label('فایل اکسل')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->directory('temp-uploads')
                            ->helperText('فایل xlsx با ستون‌های: کد دستگاه | شماره سیمکارت | سریال سیمکارت'),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateDevice),

                // فرم Edit
                Forms\Components\Section::make('اطلاعات دستگاه')
                    ->schema([
                        Forms\Components\TextInput::make('serial_number')
                            ->label('سریال دستگاه')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->placeholder('مثال: SN-123456789'),
                        
                        Forms\Components\Select::make('type')
                            ->label('نوع دستگاه')
                            ->options([
                                'GPS Tracker' => 'GPS Tracker',
                                'Fleet Management' => 'Fleet Management',
                                'Temperature Sensor' => 'Temperature Sensor',
                                'Fuel Monitor' => 'Fuel Monitor',
                                'Speed Limiter' => 'Speed Limiter',
                            ])
                            ->required()
                            ->searchable()
                            ->native(false),
                        
                        Forms\Components\DatePicker::make('manufacturing_date')
                            ->label('تاریخ تولید')
                            ->nullable()
                            ->maxDate(now()),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditDevice)
                    ->columns(2),

                Forms\Components\Section::make('اطلاعات سیمکارت')
                    ->schema([
                        Forms\Components\TextInput::make('sim_number')
                            ->label('شماره سیمکارت')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('09xxxxxxxxx'),
                    
                        Forms\Components\TextInput::make('sim_serial')
                            ->label('سریال سیمکارت')
                            ->maxLength(255)
                            ->placeholder('8998xxxxxxxxxx'),
                        
                        Forms\Components\Toggle::make('has_sim')
                            ->label('دارای سیمکارت')
                            ->default(false),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditDevice)
                    ->columns(2),

                Forms\Components\Section::make('وضعیت')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('وضعیت دستگاه')
                            ->options([
                                'available' => '✅ موجود',
                                'assigned' => '📋 اختصاص داده شده',
                                'installed' => '✅ نصب شده',
                                'faulty' => '⚠️ معیوب',
                                'maintenance' => '🔧 در تعمیر',
                                'returned' => '↩️ مرجوع شده',
                            ])
                            ->required()
                            ->native(false)
                            ->default('available'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('یادداشت')
                            ->rows(3)
                            ->placeholder('توضیحات درباره دستگاه...')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditDevice)
                    ->columns(1),

                Forms\Components\Section::make('مرجوعی')
                    ->schema([
                        Forms\Components\Toggle::make('is_returned')
                            ->label('مرجوع شده')
                            ->live(),
                        
                        Forms\Components\Textarea::make('return_reason')
                            ->label('دلیل مرجوعی')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get) => $get('is_returned')),
                    ])
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('سریال دستگاه')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-qr-code')
                    ->weight('bold')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
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
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => fn ($state) => in_array($state, ['available', 'installed']),
                        'heroicon-o-clipboard-document-list' => 'assigned',
                        'heroicon-o-exclamation-triangle' => 'faulty',
                        'heroicon-o-wrench' => 'maintenance',
                        'heroicon-o-arrow-uturn-left' => 'returned',
                    ]),
                
                Tables\Columns\TextColumn::make('assignedToRegistration.full_name')
                    ->label('اختصاص به')
                    ->searchable()
                    ->toggleable()
                    ->default('—')
                    ->icon('heroicon-o-user'),
                
                Tables\Columns\IconColumn::make('has_sim')
                    ->label('سیمکارت')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('sim_number')
                    ->label('شماره سیم')
                    ->toggleable()
                    ->default('—')
                    ->copyable(),
                
                Tables\Columns\IconColumn::make('is_returned')
                    ->label('مرجوعی')
                    ->boolean()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('manufacturing_date')
                    ->label('تاریخ تولید')
                    ->date('Y/m/d')
                    ->toggleable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('ثبت توسط')
                    ->toggleable()
                    ->default('—'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت‌نام')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalali($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'available' => 'موجود',
                        'assigned' => 'اختصاص داده شده',
                        'installed' => 'نصب شده',
                        'faulty' => 'معیوب',
                        'maintenance' => 'در تعمیر',
                        'returned' => 'مرجوع شده',
                    ]),
                
                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع دستگاه')
                    ->options([
                        'GPS Tracker' => 'GPS Tracker',
                        'Fleet Management' => 'Fleet Management',
                        'Temperature Sensor' => 'Temperature Sensor',
                        'Fuel Monitor' => 'Fuel Monitor',
                        'Speed Limiter' => 'Speed Limiter',
                    ]),
                
                Tables\Filters\TernaryFilter::make('has_sim')
                    ->label('سیمکارت')
                    ->placeholder('همه')
                    ->trueLabel('دارای سیم')
                    ->falseLabel('بدون سیم'),
                
                Tables\Filters\TernaryFilter::make('is_returned')
                    ->label('مرجوعی')
                    ->placeholder('همه')
                    ->trueLabel('فقط مرجوعی‌ها')
                    ->falseLabel('غیر مرجوعی'),
            ])            
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('assign_to_person')
                        ->label('اختصاص به متقاضی')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->visible(fn (Device $record) => $record->status === 'available' && $record->has_sim)
                        ->form([
                            Forms\Components\Select::make('registration_id')
                                ->label('انتخاب متقاضی')
                                ->options(
                                    Registration::where('status', 'financial_approved')
                                        ->whereNull('assigned_device_id')
                                        ->pluck('full_name', 'id')
                                )
                                ->searchable()
                                ->required()
                                ->helperText('فقط متقاضیان تایید مالی شده نمایش داده می‌شوند'),
                        ])
                        ->action(function (Device $record, array $data) {
                            $registration = Registration::find($data['registration_id']);
                            
                            $record->update([
                                'status' => 'assigned',
                                'assigned_to_registration_id' => $registration->id,
                            ]);
                            
                            $registration->update([
                                'status' => 'device_assigned',
                                'assigned_device_id' => $record->id,
                                'device_assigned_by' => auth()->id(),
                                'device_assigned_at' => now(),
                            ]);
                            
                            Notification::make()
                                ->success()
                                ->title('دستگاه اختصاص داده شد')
                                ->body("دستگاه {$record->serial_number} به {$registration->full_name} اختصاص یافت")
                                ->send();
                        }),

                    Tables\Actions\Action::make('change_status')
                        ->label('تغییر وضعیت')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('وضعیت جدید')
                                ->options([
                                    'available' => '✅ موجود',
                                    'faulty' => '⚠️ معیوب',
                                    'maintenance' => '🔧 در تعمیر',
                                    'returned' => '↩️ مرجوع شده',
                                ])
                                ->required(),
                            
                            Forms\Components\Textarea::make('note')
                                ->label('یادداشت')
                                ->rows(3),
                        ])
                        ->action(function (Device $record, array $data) {
                            $oldStatus = $record->status;
                            
                            $record->update([
                                'status' => $data['status'],
                                'notes' => $data['note'] ?? $record->notes,
                            ]);

                            // اگه دستگاه معیوب یا مرجوع شد، مشتری مرتبط برگرده
                            if (in_array($data['status'], ['faulty', 'maintenance', 'returned'])) {
                                $registration = Registration::where('assigned_device_id', $record->id)->first();
                                if ($registration) {
                                    $oldStatus = $registration->status;
                                    $registration->update([
                                        'status' => 'financial_approved',
                                        'assigned_device_id' => null,
                                        'device_assigned_by' => null,
                                        'device_assigned_at' => null,
                                        'installer_id' => null,
                                        'sim_activated' => false,
                                        'device_tested' => false,
                                        'preparation_approved_by' => null,
                                        'preparation_approved_at' => null,
                                        'installation_completed_at' => null,
                                        'installation_note' => "دستگاه {$record->serial_number} از وضعیت {$oldStatus} به {$data['status']} تغییر کرد",
                                    ]);

                                    $record->update(['assigned_to_registration_id' => null]);

                                    Notification::make()
                                        ->warning()
                                        ->title("مشتری {$registration->full_name} به انتظار اختصاص دستگاه برگشت")
                                        ->body("وضعیت قبلی: {$oldStatus}")
                                        ->send();
                                    ActivityLog::log('status_change', "مشتری {$registration->full_name} به انتظار اختصاص برگشت — دستگاه {$record->serial_number} به {$data['status']}", $registration);
                                }
                            }

                            Notification::make()
                                ->success()
                                ->title('وضعیت تغییر کرد')
                                ->body("وضعیت دستگاه {$record->serial_number} به‌روزرسانی شد")
                                ->send();
                            ActivityLog::log('status_change', "تغییر وضعیت دستگاه {$record->serial_number} به {$data['status']} توسط " . auth()->user()->name, $record);
                        }),
                    
                    Tables\Actions\EditAction::make()->label('ویرایش'),
                    Tables\Actions\DeleteAction::make()->label('حذف')
                    ->before(function (Device $record) {
                        // اگه دستگاه به مشتری اختصاص داده شده، مشتری رو برگردون به تأیید مالی
                        $registration = Registration::where('assigned_device_id', $record->id)->first();
                        if ($registration) {
                            $registration->update([
                                'status' => 'pending',
                                'assigned_device_id' => null,
                                'device_assigned_by' => null,
                                'device_assigned_at' => null,
                            ]);
                        }
                    }),
            ])
            ->icon('heroicon-o-ellipsis-vertical')
            ->tooltip('عملیات')
            ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('print_labels')
                        ->label('چاپ لیبل PDF')
                        ->icon('heroicon-o-printer')
                        ->color('warning')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $invalid = $records->filter(function ($device) {
                                return empty($device->serial_number)
                                    || is_null($device->assigned_to_registration_id);
                            });

                            if ($invalid->isNotEmpty()) {
                                $names = $invalid
                                    ->pluck('serial_number')
                                    ->map(fn($s) => $s ?: '(بدون سریال)')
                                    ->join('، ');

                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('خطا در چاپ لیبل')
                                    ->body("این دستگاه‌ها سریال ندارند یا به متقاضی اختصاص نشده‌اند: {$names}")
                                    ->persistent()
                                    ->send();

                                return;
                            }

                            $ids = $records->pluck('id')->join(',');

                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('لیبل‌ها آماده‌اند')
                                ->body('<a href="' . route('labels.pdf', ['ids' => $ids]) . '" target="_blank" style="color:white;font-weight:bold;text-decoration:underline">📄 کلیک کنید برای دانلود PDF</a>')
                                ->persistent()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('هیچ دستگاهی ثبت نشده')
            ->emptyStateDescription('برای شروع، دستگاه جدید اضافه کنید')
            ->emptyStateIcon('heroicon-o-cpu-chip')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('ثبت دستگاه جدید')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
            'without-sim' => Pages\DevicesWithoutSim::route('/without-sim'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $withoutSimCount = Device::withoutSim()->count();
        return $withoutSimCount > 0 ? (string) $withoutSimCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}