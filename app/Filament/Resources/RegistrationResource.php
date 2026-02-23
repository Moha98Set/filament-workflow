<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\ActivityLog;
use App\Models\Registration;
use App\Models\Device;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'مدیریت ثبت‌نام‌ها';
    
    protected static ?string $navigationGroup = 'مدیریت';
    
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin', 'operator']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('اطلاعات')
                    ->tabs([
                        // تب 1: اطلاعات شخصی
                        Forms\Components\Tabs\Tab::make('اطلاعات شخصی')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\TextInput::make('full_name')
                                    ->label('نام و نام خانوادگی')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('phone')
                                    ->label('شماره تلفن')
                                    ->tel()
                                    ->required()
                                    ->maxLength(11)
                                    ->minLength(11)
                                    ->regex('/^09[0-9]{9}$/')
                                    ->validationMessages([
                                        'regex' => 'شماره تلفن باید با 09 شروع شود و 11 رقم باشد',
                                        'min_length' => 'شماره تلفن باید 11 رقم باشد',
                                    ])
                                    ->extraInputAttributes([
                                        'maxlength' => 11,
                                        'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);",
                                        'onkeypress' => "
                                            if (this.value.length >= 11 && !document.getElementById('limitToast')) {
                                                var toast = document.createElement('div');
                                                toast.id = 'limitToast';
                                                toast.innerHTML = '<div style=\"display:flex;align-items:center;gap:12px;\"><span style=\"font-size:24px;\">⚠️</span><div><div style=\"font-weight:700;font-size:14px;margin-bottom:2px;\">محدودیت شماره تلفن</div><div style=\"font-size:12px;color:#666;\">حداکثر ۱۱ رقم مجاز است</div></div><button onclick=\"this.parentElement.parentElement.style.opacity=0;setTimeout(function(){document.getElementById(\\\\\"limitToast\\\\\").remove()},300)\" style=\"margin-right:auto;background:none;border:none;font-size:18px;cursor:pointer;color:#999;padding:4px 8px;\">✕</button></div>';
                                                toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:white;padding:16px 24px;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.15);border-right:4px solid #f59e0b;z-index:99999;font-family:Vazirmatn,sans-serif;direction:rtl;opacity:0;transition:all 0.4s cubic-bezier(0.68,-0.55,0.265,1.55);min-width:300px;';
                                                document.body.appendChild(toast);
                                                requestAnimationFrame(function(){ toast.style.opacity='1'; toast.style.top='40px'; });
                                                setTimeout(function(){ if(document.getElementById('limitToast')){ toast.style.opacity='0'; toast.style.top='20px'; setTimeout(function(){ if(document.getElementById('limitToast')) toast.remove(); },400); }}, 3000);
                                            }
                                        ",
                                    ]),

                                Forms\Components\TextInput::make('national_id')
                                    ->label('کد ملی')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10)
                                    ->minLength(10)
                                    ->regex('/^[0-9]{10}$/')
                                    ->validationMessages([
                                        'regex' => 'کد ملی باید 10 رقم باشد',
                                        'min_length' => 'کد ملی باید 10 رقم باشد',
                                    ])
                                    ->extraInputAttributes([
                                        'maxlength' => 10,
                                        'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);",
                                        'onkeypress' => "
                                            if (this.value.length >= 10 && !document.getElementById('limitToast2')) {
                                                var toast = document.createElement('div');
                                                toast.id = 'limitToast2';
                                                toast.innerHTML = '<div style=\"display:flex;align-items:center;gap:12px;\"><span style=\"font-size:24px;\">⚠️</span><div><div style=\"font-weight:700;font-size:14px;margin-bottom:2px;\">محدودیت کد ملی</div><div style=\"font-size:12px;color:#666;\">حداکثر ۱۰ رقم مجاز است</div></div><button onclick=\"this.parentElement.parentElement.style.opacity=0;setTimeout(function(){document.getElementById(\\\\\"limitToast2\\\\\").remove()},300)\" style=\"margin-right:auto;background:none;border:none;font-size:18px;cursor:pointer;color:#999;padding:4px 8px;\">✕</button></div>';
                                                toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:white;padding:16px 24px;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.15);border-right:4px solid #ef4444;z-index:99999;font-family:Vazirmatn,sans-serif;direction:rtl;opacity:0;transition:all 0.4s cubic-bezier(0.68,-0.55,0.265,1.55);min-width:300px;';
                                                document.body.appendChild(toast);
                                                requestAnimationFrame(function(){ toast.style.opacity='1'; toast.style.top='40px'; });
                                                setTimeout(function(){ if(document.getElementById('limitToast2')){ toast.style.opacity='0'; toast.style.top='20px'; setTimeout(function(){ if(document.getElementById('limitToast2')) toast.remove(); },400); }}, 3000);
                                            }
                                        ",
                                    ]),

                                Forms\Components\Select::make('province')
                                    ->label('استان')
                                    ->required()
                                    ->searchable()
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

                                Forms\Components\Select::make('organization')
                                    ->label('سازمان')
                                    ->required()
                                    ->searchable()
                                    ->options([
                                        'jihad' => 'جهاد کشاورزی',
                                        'sanat' => 'صنعت معدن و تجارت',
                                        'shilat' => 'سازمان شیلات',
                                    ]),
                                
                                Forms\Components\TextInput::make('city')
                                    ->label('شهر')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('district')
                                    ->label('بخش')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('village')
                                    ->label('روستا')
                                    ->required(),
                                
                                Forms\Components\Textarea::make('installation_address')
                                    ->label('آدرس نصب')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->columns(2),

                        // تب 2: بررسی مالی
                        Forms\Components\Tabs\Tab::make('بررسی مالی')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Forms\Components\Placeholder::make('status_info')
                                    ->label('وضعیت فعلی')
                                    ->content(fn ($record) => $record?->status_label ?? 'در انتظار ثبت')
                                    ->visible(fn ($record) => $record !== null),
                                
                                Forms\Components\FileUpload::make('payment_receipt')
                                    ->label('فیش واریزی')
                                    ->image()
                                    ->maxSize(2048)
                                    ->directory('payment-receipts')
                                    ->visible(fn ($record) => $record?->status === 'pending'),
                                
                                Forms\Components\TextInput::make('payment_amount')
                                    ->label('مبلغ پرداختی (ریال)')
                                    ->numeric()
                                    ->prefix('ریال')
                                    ->disabled(fn ($record) => $record?->status !== 'pending'),
                                
                                Forms\Components\TextInput::make('transaction_id')
                                    ->label('شماره تراکنش')
                                    ->maxLength(100)
                                    ->disabled(fn ($record) => $record?->status !== 'pending'),
                                
                                Forms\Components\Textarea::make('financial_note')
                                    ->label('یادداشت مالی')
                                    ->rows(3)
                                    ->disabled(fn ($record) => $record?->financial_approved_by !== null && $record?->financial_approved_by !== auth()->id()),
                                
                                Forms\Components\Placeholder::make('financial_info')
                                    ->label('اطلاعات تایید مالی')
                                    ->content(function ($record) {
                                        if (!$record || !$record->financial_approved_by) {
                                            return 'هنوز تایید نشده';
                                        }
                                        return "تایید شده توسط: {$record->financialApprover->name} در تاریخ " . $record->financial_approved_at->format('Y/m/d H:i');
                                    })
                                    ->visible(fn ($record) => $record?->financial_approved_by !== null),
                            ])
                            ->columns(2),

                        // تب 3: اختصاص دستگاه
                        Forms\Components\Tabs\Tab::make('اختصاص دستگاه')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Forms\Components\Select::make('assigned_device_id')
                                    ->label('انتخاب دستگاه')
                                    ->options(function () {
                                        return Device::where('status', 'available')
                                            ->where('has_sim', true)
                                            ->pluck('serial_number', 'id'); // تغییر از serial به serial_number
                                    })
                                    ->searchable()
                                    ->helperText('فقط دستگاه‌های موجود و دارای سیمکارت'),
                                
                                Forms\Components\Textarea::make('device_assignment_note')
                                    ->label('یادداشت اختصاص دستگاه')
                                    ->rows(3)
                                    ->disabled(fn ($record) => $record?->device_assigned_by !== null && $record?->device_assigned_by !== auth()->id()),
                                
                                Forms\Components\Placeholder::make('device_info')
                                    ->label('اطلاعات دستگاه')
                                    ->content(function ($record) {
                                        if (!$record || !$record->assignedDevice) {
                                            return 'هنوز دستگاهی اختصاص نیافته';
                                        }
                                        $device = $record->assignedDevice;
                                        return "دستگاه: {$device->code} | نوع: {$device->type} | سریال: {$device->serial_number}";
                                    })
                                    ->visible(fn ($record) => $record?->assigned_device_id !== null),
                                
                                Forms\Components\Placeholder::make('assignment_info')
                                    ->label('اطلاعات اختصاص')
                                    ->content(function ($record) {
                                        if (!$record || !$record->device_assigned_by) {
                                            return 'هنوز اختصاص داده نشده';
                                        }
                                        return "توسط: {$record->deviceAssigner->name} در تاریخ " . $record->device_assigned_at->format('Y/m/d H:i');
                                    })
                                    ->visible(fn ($record) => $record?->device_assigned_by !== null),
                            ])
                            ->columns(1),
                        
                        // تب: آماده‌سازی دستگاه
                        Forms\Components\Tabs\Tab::make('آماده‌سازی دستگاه')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                Forms\Components\Placeholder::make('prep_status')
                                    ->label('وضعیت آماده‌سازی')
                                    ->content(function ($record) {
                                        if (!$record) return 'ثبت‌نام جدید';
                                        if ($record->status !== 'device_assigned') return 'این مرحله هنوز فعال نشده';
                                        if ($record->sim_activated && $record->device_tested) return '✅ آماده انتقال به نصاب';
                                        return '⏳ در انتظار تأیید چک‌لیست';
                                    }),

                                Forms\Components\Toggle::make('sim_activated')
                                    ->label('سیمکارت فعال شده')
                                    ->helperText('آیا سیمکارت دستگاه فعال و تست شده است؟')
                                    ->disabled(fn ($record) => 
                                        !$record || 
                                        $record->status !== 'device_assigned' || 
                                        (!auth()->user()->hasRole(['super_admin', 'admin']) && auth()->user()->operator_tag !== 'کارشناس فنی')
                                    ),

                                Forms\Components\Toggle::make('device_tested')
                                    ->label('دستگاه تست شده')
                                    ->helperText('آیا دستگاه به درستی کار می‌کند و تست شده است؟')
                                    ->disabled(fn ($record) => 
                                        !$record || 
                                        $record->status !== 'device_assigned' || 
                                        (!auth()->user()->hasRole(['super_admin', 'admin']) && auth()->user()->operator_tag !== 'کارشناس فنی')
                                    ),

                                Forms\Components\Textarea::make('preparation_note')
                                    ->label('یادداشت آماده‌سازی')
                                    ->rows(3)
                                    ->disabled(fn ($record) => 
                                        !$record || 
                                        $record->status !== 'device_assigned' || 
                                        (!auth()->user()->hasRole(['super_admin', 'admin']) && auth()->user()->operator_tag !== 'کارشناس فنی')
                                    ),

                                Forms\Components\Placeholder::make('prep_info')
                                    ->label('اطلاعات تأیید')
                                    ->content(function ($record) {
                                        if (!$record || !$record->preparation_approved_by) return 'هنوز تأیید نشده';
                                        return "تأیید توسط: {$record->preparationApprover->name} در تاریخ " . $record->preparation_approved_at->format('Y/m/d H:i');
                                    })
                                    ->visible(fn ($record) => $record?->preparation_approved_by !== null),
                            ])
                            ->columns(1),
                        // تب 4: نصب
                        Forms\Components\Tabs\Tab::make('نصب')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Forms\Components\Select::make('installer_id')
                                    ->label('نصاب')
                                    ->options(function () {
                                        return User::whereHas('roles', function ($query) {
                                            $query->where('name', 'operator');
                                        })
                                        ->where('operator_tag', 'نصاب')
                                        ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->disabled(fn ($record) => $record?->status !== 'device_assigned'),
                                
                                Forms\Components\DateTimePicker::make('installation_scheduled_at')
                                    ->label('زمان برنامه‌ریزی نصب')
                                    ->disabled(fn ($record) => $record?->installer_id === null),
                                
                                Forms\Components\DateTimePicker::make('installation_completed_at')
                                    ->label('زمان اتمام نصب')
                                    ->disabled(fn ($record) => $record?->installer_id !== auth()->id()),
                                
                                Forms\Components\Textarea::make('installation_note')
                                    ->label('یادداشت نصب')
                                    ->rows(3)
                                    ->disabled(fn ($record) => $record?->installer_id !== auth()->id()),
                                
                                Forms\Components\FileUpload::make('installation_photos')
                                    ->label('عکس‌های نصب')
                                    ->image()
                                    ->multiple()
                                    ->maxSize(5120)
                                    ->directory('installation-photos')
                                    ->disabled(fn ($record) => $record?->installer_id !== auth()->id()),
                            ])
                            ->columns(2),

                        // تب 5: مرجوعی و جابجایی
                        Forms\Components\Tabs\Tab::make('مرجوعی/جابجایی')
                            ->icon('heroicon-o-arrow-path')
                            ->schema([
                                Forms\Components\Toggle::make('is_returned')
                                    ->label('مرجوع شده')
                                    ->live(),
                                
                                Forms\Components\Textarea::make('return_reason')
                                    ->label('دلیل مرجوعی')
                                    ->rows(3)
                                    ->visible(fn (Forms\Get $get) => $get('is_returned')),
                                
                                Forms\Components\Toggle::make('is_relocated')
                                    ->label('جابجا شده')
                                    ->helperText('آیا دستگاه به شخص دیگری منتقل شده؟'),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('full_name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->icon('heroicon-o-phone'),
                
                Tables\Columns\TextColumn::make('province')
                    ->label('استان')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->color(fn ($record) => $record->status_color),
                
                Tables\Columns\TextColumn::make('assignedDevice.serial_number')
                    ->label('دستگاه')
                    ->default('—')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('organization')
                    ->label('سازمان')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'jihad',
                        'danger' => 'sanat',
                        'info' => 'shilat',
                    ])
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organization')
                    ->label('سازمان')
                    ->options([
                        'jihad' => 'جهاد کشاورزی',
                        'sanat' => 'صنعت معدن و تجارت',
                        'shilat' => 'سازمان شیلات',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار بررسی مالی',
                        'financial_approved' => 'تایید مالی شده',
                        'financial_rejected' => 'رد مالی',
                        'device_assigned' => 'دستگاه اختصاص داده شد',
                        'ready_for_installation' => 'آماده نصب',
                        'installed' => 'نصب شده',
                        'installation_failed' => 'نصب ناموفق',
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
                    ]),
            ])
            ->modifyQueryUsing(function ($query) use ($user) {
                // فیلتر خودکار بر اساس نقش
                if ($user->hasRole(['super_admin', 'admin'])) {
                    // ادمین‌ها همه چیز را می‌بینند
                    return $query;
                }
                
                // اپراتور مالی فقط pending و financial_approved را می‌بیند
                if ($user->operator_tag === 'کارشناس مالی') {
                    return $query->whereIn('status', ['pending', 'financial_approved', 'financial_rejected']);
                }
                
                // اپراتور فنی فقط financial_approved و device_assigned را می‌بیند
                if ($user->operator_tag === 'کارشناس فنی') {
                    return $query->whereIn('status', ['financial_approved', 'device_assigned']);
                }
                
                // نصاب فقط device_assigned به بعد را می‌بیند
                if ($user->operator_tag === 'نصاب') {
                    return $query->where('installer_id', $user->id)
                        ->orWhereIn('status', ['device_assigned', 'ready_for_installation', 'installed']);
                }
                
                return $query;
            })
            ->actions([
                Tables\Actions\ActionGroup::make([
                // اکشن تایید مالی
                Tables\Actions\Action::make('financial_approve')
                    ->label('تایید مالی')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending' && (auth()->user()->hasRole(['super_admin', 'admin']) || auth()->user()->operator_tag === 'کارشناس مالی'))
                    ->requiresConfirmation()
                    ->modalHeading('تایید مالی')
                    ->modalDescription('آیا از تایید مالی این درخواست اطمینان دارید؟')
                    ->form([
                        Forms\Components\TextInput::make('payment_amount')
                            ->label('مبلغ پرداختی')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('شماره تراکنش'),
                        Forms\Components\Textarea::make('note')
                            ->label('یادداشت')
                            ->rows(3),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $record->markAsFinancialApproved(auth()->user(), [
                            'amount' => $data['payment_amount'],
                            'transaction_id' => $data['transaction_id'] ?? null,
                            'note' => $data['note'] ?? null,
                        ]);
                        
                        // ارسال نوتیفیکیشن به اپراتور فنی
                        $technicalExperts = User::where('operator_tag', 'کارشناس فنی')->get();
                        foreach ($technicalExperts as $expert) {
                            Notification::make()
                                ->success()
                                ->title('درخواست جدید آماده اختصاص دستگاه')
                                ->body("درخواست {$record->full_name} تایید مالی شد")
                                ->actions([
                                    NotificationAction::make('view')
                                        ->label('مشاهده')
                                        ->url(RegistrationResource::getUrl('edit', ['record' => $record])),
                                ])
                                ->sendToDatabase($expert);
                        }
                        
                        Notification::make()
                            ->success()
                            ->title('تایید مالی انجام شد')
                            ->send();
                        ActivityLog::log('financial_approved', "تأیید مالی {$record->full_name} توسط " . auth()->user()->name, $record);
                        // نوتیفیکیشن به کارشناس فنی و ادمین
                        $techUsers = \App\Models\User::where('operator_tag', 'کارشناس فنی')
                            ->orWhereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))
                            ->get();
                        foreach ($techUsers as $user) {
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('تأیید مالی جدید')
                                ->body("{$record->full_name} — منتظر اختصاص دستگاه")
                                ->icon('heroicon-o-check-circle')
                                ->sendToDatabase($user);
                        }
                    }),
                
                // اکشن رد مالی
                Tables\Actions\Action::make('financial_reject')
                    ->label('رد مالی')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending' && (auth()->user()->hasRole(['super_admin', 'admin']) || auth()->user()->operator_tag === 'کارشناس مالی'))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('دلیل رد')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $record->update([
                            'status' => 'financial_rejected',
                            'financial_note' => $data['reason'],
                        ]);
                        
                        Notification::make()
                            ->danger()
                            ->title('درخواست رد شد')
                            ->send();
                        ActivityLog::log('financial_rejected', "رد مالی {$record->full_name} توسط " . auth()->user()->name, $record);
                        //notif
                        $admins = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))->get();
                        foreach ($admins as $u) { \Filament\Notifications\Notification::make()->danger()->title('رد مالی')->body("{$record->full_name}")->icon('heroicon-o-x-circle')->sendToDatabase($u); }
                    }),
                
                // اکشن اختصاص دستگاه
                Tables\Actions\Action::make('assign_device')
                    ->label('اختصاص دستگاه')
                    ->icon('heroicon-o-cpu-chip')
                    ->color('success')
                    ->visible(fn (Registration $record) => 
                        $record->status === 'financial_approved' && 
                        !$record->assigned_device_id &&
                        (auth()->user()->hasRole(['super_admin', 'admin']) || 
                        auth()->user()->operator_tag === 'کارشناس فنی')
                    )
                    ->form([
                        Forms\Components\Select::make('device_id')
                            ->label('انتخاب دستگاه')
                            ->options(function () {
                                return Device::where('status', 'available')
                                    ->where('has_sim', true) // ✅ این خط رو چک کنید وجود داره
                                    ->get()
                                    ->mapWithKeys(function ($device) {
                                        return [
                                            $device->id => sprintf(
                                                '%s | %s | سیم: %s',
                                                $device->serial_number,
                                                $device->type,
                                                $device->sim_number ?? 'ندارد'
                                            )
                                        ];
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->helperText('⚠️ فقط دستگاه‌های موجود و دارای سیمکارت نمایش داده می‌شوند')
                            ->placeholder('یک دستگاه انتخاب کنید')
                            ->native(false),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $device = Device::find($data['device_id']);
                        $record->assignDevice(auth()->user(), $device);
                        
                        // ارسال نوتیفیکیشن به نصاب‌ها
                        $installers = User::where('operator_tag', 'نصاب')->get();
                        foreach ($installers as $installer) {
                            Notification::make()
                                ->success()
                                ->title('دستگاه جدید آماده نصب')
                                ->body("دستگاه {$device->code} برای {$record->full_name}")
                                ->sendToDatabase($installer);
                        }
                        
                        Notification::make()
                            ->success()
                            ->title('دستگاه اختصاص داده شد')
                            ->send();
                        ActivityLog::log('device_assigned', "اختصاص دستگاه {$device->serial_number} به {$record->full_name} توسط " . auth()->user()->name, $record);
                        // نوتیفیکیشن به کارشناس فنی
                        $techUsers = \App\Models\User::where('operator_tag', 'کارشناس فنی')
                            ->orWhereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))
                            ->get();
                        foreach ($techUsers as $user) {
                            \Filament\Notifications\Notification::make()
                                ->info()
                                ->title('دستگاه اختصاص داده شد')
                                ->body("{$record->full_name} — {$device->serial_number}")
                                ->icon('heroicon-o-cpu-chip')
                                ->sendToDatabase($user);
                        }
                    }),

                Tables\Actions\Action::make('approve_preparation')
                    ->label('تأیید آماده‌سازی')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn (Registration $record) => 
                        $record->status === 'device_assigned' && 
                        !$record->preparation_approved_by &&
                        (auth()->user()->hasRole(['super_admin', 'admin']) || auth()->user()->operator_tag === 'کارشناس فنی')
                    )
                    ->form([
                        Forms\Components\Toggle::make('sim_activated')
                            ->label('سیمکارت سالم')
                            ->required()
                            ->accepted(),

                        Forms\Components\Toggle::make('device_tested')
                            ->label('چراغ‌ها سالم')
                            ->required()
                            ->accepted(),

                        Forms\Components\Toggle::make('final_test')
                            ->label('تست نهایی')
                            ->required()
                            ->accepted(),

                        Forms\Components\Toggle::make('server_changed')
                            ->label('تغییر سرور')
                            ->required()
                            ->accepted(),

                        Forms\Components\Textarea::make('note')
                            ->label('یادداشت')
                            ->rows(2),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $record->update([
                            'sim_activated' => true,
                            'device_tested' => true,
                            'preparation_note' => $data['note'] ?? null,
                            'preparation_approved_by' => auth()->id(),
                            'preparation_approved_at' => now(),
                            'status' => 'ready_for_installation',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('دستگاه آماده انتقال به نصاب شد')
                            ->send();
                        ActivityLog::log('preparation_approved', "تأیید آماده‌سازی {$record->full_name} توسط " . auth()->user()->name, $record);
                        // نوتیفیکیشن به نصاب‌ها
                        $installers = \App\Models\User::where('operator_tag', 'نصاب')->where('is_active', true)->get();
                        foreach ($installers as $installer) {
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('دستگاه آماده نصب')
                                ->body("{$record->full_name} — آماده انتقال به نصاب")
                                ->icon('heroicon-o-wrench-screwdriver')
                                ->sendToDatabase($installer);
                        }
                    }),

                Tables\Actions\Action::make('report_faulty')
                    ->label('گزارش معیوبی')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn (Registration $record) => 
                        $record->status === 'device_assigned' && 
                        $record->assigned_device_id &&
                        (auth()->user()->hasRole(['super_admin', 'admin']) || auth()->user()->operator_tag === 'کارشناس فنی')
                    )
                    ->requiresConfirmation()
                    ->modalHeading('گزارش دستگاه معیوب')
                    ->modalDescription('دستگاه از مشتری جدا شده و به بخش معیوب‌ها منتقل می‌شود. مشتری به مرحله انتظار اختصاص دستگاه برمی‌گردد.')
                    ->form([
                        Forms\Components\Textarea::make('fault_reason')
                            ->label('دلیل معیوبی')
                            ->required()
                            ->rows(3)
                            ->placeholder('توضیح دهید چه مشکلی با دستگاه وجود دارد...'),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $device = \App\Models\Device::find($record->assigned_device_id);
                        
                        if ($device) {
                            $device->update([
                                'status' => 'faulty',
                                'notes' => $data['fault_reason'],
                                'assigned_to_registration_id' => null,
                            ]);
                        }

                        $record->update([
                            'status' => 'financial_approved',
                            'assigned_device_id' => null,
                            'device_assigned_by' => null,
                            'device_assigned_at' => null,
                            'sim_activated' => false,
                            'device_tested' => false,
                            'preparation_approved_by' => null,
                            'preparation_approved_at' => null,
                            'preparation_note' => null,
                        ]);

                        Notification::make()
                            ->warning()
                            ->title('دستگاه معیوب گزارش شد')
                            ->body("دستگاه {$device?->serial_number} به معیوب‌ها منتقل شد و مشتری {$record->full_name} به انتظار اختصاص دستگاه برگشت")
                            ->send();
                        ActivityLog::log('device_faulty', "دستگاه معیوب گزارش شد — مشتری: {$record->full_name}", $record);
                        //notif
                        $admins = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))->get();
                        foreach ($admins as $u) { \Filament\Notifications\Notification::make()->danger()->title('دستگاه معیوب')->body("{$record->full_name}")->icon('heroicon-o-exclamation-triangle')->sendToDatabase($u); }
                    }),
                
                Tables\Actions\Action::make('assign_installer')
                    ->label('انتقال به نصاب')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (Registration $record) => 
                        $record->status === 'ready_for_installation' && 
                        !$record->installer_id &&
                        (auth()->user()->hasRole(['super_admin', 'admin']) || auth()->user()->operator_tag === 'کارشناس فنی')
                    )
                    ->form([
                        Forms\Components\Select::make('installer_id')
                            ->label('انتخاب نصاب')
                            ->options(function () {
                                return \App\Models\User::where('operator_tag', 'نصاب')
                                    ->where('is_active', true)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->helperText('فقط نصاب‌های فعال نمایش داده می‌شوند'),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $record->update([
                            'installer_id' => $data['installer_id'],
                        ]);

                        $installer = \App\Models\User::find($data['installer_id']);

                        Notification::make()
                            ->success()
                            ->title("به {$installer->name} انتقال داده شد")
                            ->body("مشتری {$record->full_name} برای نصب به {$installer->name} اختصاص یافت")
                            ->send();
                        ActivityLog::log('device_transfer', "جابجایی: {$record->full_name} — نوع: {$data['action_type']}", $record);
                        // نوتیفیکیشن به ادمین
                        $admins = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin']))->get();
                        foreach ($admins as $admin) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('جابجایی انجام شد')
                                ->body("{$record->full_name}")
                                ->icon('heroicon-o-arrow-path')
                                ->sendToDatabase($admin);
                        }
                    }),
                Tables\Actions\Action::make('handle_relocation')
                    ->label('بررسی جابجایی')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Registration $record) => 
                        $record->status === 'relocation_requested' && 
                        auth()->user()->hasRole(['super_admin', 'admin'])
                    )
                    ->form([
                        Forms\Components\Placeholder::make('request_info')
                            ->label('اطلاعات درخواست')
                            ->content(fn (Registration $record) => 
                                "👤 متقاضی: {$record->full_name} | 📞 تلفن: {$record->phone} | 📱 دستگاه: " . ($record->assignedDevice?->serial_number ?? '—')
                            ),

                        Forms\Components\Placeholder::make('reason')
                            ->label('دلیل درخواست نصاب')
                            ->content(fn (Registration $record) => $record->installation_note ?? '—'),

                        Forms\Components\Select::make('action_type')
                            ->label('تصمیم')
                            ->required()
                            ->options([
                                'swap' => '🔄 جابجایی متقابل (هر دو دستگاه دارن)',
                                'transfer' => '➡️ انتقال دستگاه (به متقاضی بدون دستگاه)',
                                'replace' => '🔧 جایگزینی معیوب (دستگاه سالم به متقاضی با دستگاه معیوب)',
                                'reject' => '❌ رد درخواست و ادامه نصب',
                            ])
                            ->live()
                            ->native(false),

                        // هشدار برای حالت‌های ۲ و ۳
                        Forms\Components\Placeholder::make('warning_transfer')
                            ->label('')
                            ->content('⚠️ توجه: متقاضی فعلی دستگاهش را از دست می‌دهد و به «در انتظار اختصاص دستگاه» برمی‌گردد.')
                            ->visible(fn (Forms\Get $get) => in_array($get('action_type'), ['transfer', 'replace'])),

                        // حالت ۱: جابجایی متقابل
                        Forms\Components\Select::make('swap_registration_id')
                            ->label('جابجایی با متقاضی (دستگاه‌دار)')
                            ->searchable()
                            ->required()
                            ->options(function (Registration $record) {
                                return Registration::whereIn('status', ['device_assigned', 'ready_for_installation'])
                                    ->where('id', '!=', $record->id)
                                    ->whereNotNull('assigned_device_id')
                                    ->get()
                                    ->mapWithKeys(fn ($reg) => [
                                        $reg->id => "👤 {$reg->full_name} | 📱 {$reg->assignedDevice?->serial_number} | 📞 {$reg->phone}"
                                    ]);
                            })
                            ->helperText('هر دو متقاضی دستگاه دارن و دستگاه‌ها جابجا می‌شن')
                            ->visible(fn (Forms\Get $get) => $get('action_type') === 'swap')
                            ->native(false),

                        // حالت ۲: انتقال به بدون دستگاه
                        Forms\Components\Select::make('transfer_registration_id')
                            ->label('انتقال به متقاضی (بدون دستگاه)')
                            ->searchable()
                            ->required()
                            ->options(function (Registration $record) {
                                return Registration::where('status', 'financial_approved')
                                    ->whereNull('assigned_device_id')
                                    ->get()
                                    ->mapWithKeys(fn ($reg) => [
                                        $reg->id => "👤 {$reg->full_name} | 📞 {$reg->phone} | 🏙️ {$reg->city}"
                                    ]);
                            })
                            ->helperText('متقاضی مقصد دستگاه ندارد — دستگاه فعلی به او منتقل می‌شود')
                            ->visible(fn (Forms\Get $get) => $get('action_type') === 'transfer')
                            ->native(false),

                        // حالت ۳: جایگزینی معیوب
                        Forms\Components\Select::make('replace_registration_id')
                            ->label('جایگزینی با متقاضی (دستگاه معیوب)')
                            ->searchable()
                            ->required()
                            ->options(function (Registration $record) {
                                return Registration::whereIn('status', ['device_assigned', 'ready_for_installation'])
                                    ->where('id', '!=', $record->id)
                                    ->whereNotNull('assigned_device_id')
                                    ->whereHas('assignedDevice', fn ($q) => $q->where('status', 'faulty'))
                                    ->get()
                                    ->mapWithKeys(fn ($reg) => [
                                        $reg->id => "👤 {$reg->full_name} | 📱 {$reg->assignedDevice?->serial_number} (معیوب) | 📞 {$reg->phone}"
                                    ]);
                            })
                            ->helperText('دستگاه سالم به این متقاضی می‌رود و دستگاه معیوبش به لیست معیوب‌ها منتقل می‌شود')
                            ->visible(fn (Forms\Get $get) => $get('action_type') === 'replace')
                            ->native(false),

                        Forms\Components\Textarea::make('admin_note')
                            ->label('یادداشت ادمین')
                            ->rows(2)
                            ->placeholder('توضیحات...')
                            ->visible(fn (Forms\Get $get) => $get('action_type') !== null),
                    ])
                    ->modalHeading('بررسی درخواست جابجایی')
                    ->modalSubmitActionLabel('اعمال تصمیم')
                    ->modalWidth('lg')
                    ->action(function (Registration $record, array $data) {
                        $note = $data['admin_note'] ?? '';

                        // حالت ۱: جابجایی متقابل
                        if ($data['action_type'] === 'swap') {
                            $toReg = Registration::find($data['swap_registration_id']);
                            $fromDevice = $record->assignedDevice;
                            $toDevice = $toReg->assignedDevice;

                            $record->update([
                                'assigned_device_id' => $toDevice->id,
                                'status' => 'ready_for_installation',
                                'installation_note' => "جابجایی متقابل با {$toReg->full_name} | " . $note,
                            ]);
                            $toReg->update([
                                'assigned_device_id' => $fromDevice->id,
                            ]);
                            $fromDevice->update(['assigned_to_registration_id' => $toReg->id]);
                            $toDevice->update(['assigned_to_registration_id' => $record->id]);

                            Notification::make()
                                ->success()
                                ->title('جابجایی متقابل انجام شد')
                                ->body("دستگاه {$fromDevice->serial_number} ↔ {$toDevice->serial_number} جابجا شد")
                                ->send();

                        // حالت ۲: انتقال دستگاه
                        } elseif ($data['action_type'] === 'transfer') {
                            $toReg = Registration::find($data['transfer_registration_id']);
                            $device = $record->assignedDevice;

                            // دستگاه به متقاضی جدید
                            $toReg->update([
                                'assigned_device_id' => $device->id,
                                'device_assigned_by' => auth()->id(),
                                'device_assigned_at' => now(),
                                'status' => 'device_assigned',
                            ]);
                            $device->update(['assigned_to_registration_id' => $toReg->id]);

                            // متقاضی اول برمیگرده به انتظار اختصاص
                            $record->update([
                                'assigned_device_id' => null,
                                'device_assigned_by' => null,
                                'device_assigned_at' => null,
                                'installer_id' => null,
                                'sim_activated' => false,
                                'device_tested' => false,
                                'preparation_approved_by' => null,
                                'preparation_approved_at' => null,
                                'status' => 'financial_approved',
                                'installation_note' => "انتقال دستگاه به {$toReg->full_name} | " . $note,
                            ]);

                            Notification::make()
                                ->success()
                                ->title('انتقال دستگاه انجام شد')
                                ->body("دستگاه {$device->serial_number} به {$toReg->full_name} منتقل شد. {$record->full_name} به انتظار اختصاص دستگاه برگشت.")
                                ->send();

                        // حالت ۳: جایگزینی معیوب
                        } elseif ($data['action_type'] === 'replace') {
                            $toReg = Registration::find($data['replace_registration_id']);
                            $healthyDevice = $record->assignedDevice;
                            $faultyDevice = $toReg->assignedDevice;

                            // دستگاه معیوب به لیست معیوب‌ها
                            $faultyDevice->update([
                                'status' => 'faulty',
                                'assigned_to_registration_id' => null,
                            ]);

                            // دستگاه سالم به متقاضی دوم
                            $toReg->update([
                                'assigned_device_id' => $healthyDevice->id,
                            ]);
                            $healthyDevice->update(['assigned_to_registration_id' => $toReg->id]);

                            // متقاضی اول برمیگرده به انتظار اختصاص
                            $record->update([
                                'assigned_device_id' => null,
                                'device_assigned_by' => null,
                                'device_assigned_at' => null,
                                'installer_id' => null,
                                'sim_activated' => false,
                                'device_tested' => false,
                                'preparation_approved_by' => null,
                                'preparation_approved_at' => null,
                                'status' => 'financial_approved',
                                'installation_note' => "جایگزینی معیوب: دستگاه به {$toReg->full_name} | دستگاه {$faultyDevice->serial_number} معیوب | " . $note,
                            ]);

                            Notification::make()
                                ->success()
                                ->title('جایگزینی معیوب انجام شد')
                                ->body("دستگاه سالم {$healthyDevice->serial_number} به {$toReg->full_name}. دستگاه معیوب {$faultyDevice->serial_number} به لیست معیوب‌ها رفت. {$record->full_name} به انتظار اختصاص برگشت.")
                                ->send();

                        // رد درخواست
                        } elseif ($data['action_type'] === 'reject') {
                            $record->update([
                                'status' => 'ready_for_installation',
                                'installation_note' => ($record->installation_note ?? '') . ' | رد درخواست: ' . $note,
                            ]);

                            Notification::make()
                                ->info()
                                ->title('درخواست جابجایی رد شد')
                                ->body("{$record->full_name} به حالت آماده نصب برگشت")
                                ->send();
                        }
                    }),

                Tables\Actions\EditAction::make()->label('ویرایش'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->color('danger')
                    ->before(function (Registration $record) {
                        if ($record->assigned_device_id) {
                            $device = \App\Models\Device::find($record->assigned_device_id);
                            if ($device) {
                                $device->update([
                                    'status' => 'available',
                                    'assigned_to_registration_id' => null,
                                ]);
                            }
                        }
                    }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        
        if ($user->operator_tag === 'کارشناس مالی') {
            return Registration::where('status', 'pending')->count() ?: null;
        }
        
        if ($user->operator_tag === 'کارشناس فنی') {
            return Registration::where('status', 'financial_approved')->count() ?: null;
        }
        
        if ($user->operator_tag === 'نصاب') {
            return Registration::where('installer_id', $user->id)
                ->where('status', 'device_assigned')
                ->count() ?: null;
        }
        
        return Registration::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}