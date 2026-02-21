<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstallerResource\Pages;
use App\Models\User;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class InstallerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'مدیریت نصاب‌ها';

    protected static ?string $navigationGroup = 'مدیریت';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'installers';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('operator_tag', 'نصاب');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات شخصی')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام و نام خانوادگی')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('شماره تلفن')
                            ->tel()
                            ->required()
                            ->maxLength(11),

                        Forms\Components\TextInput::make('national_id')
                            ->label('کد ملی')
                            ->required()
                            ->maxLength(10),

                        Forms\Components\TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label('رمز عبور')
                            ->password()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('اطلاعات سازمانی')
                    ->schema([
                        Forms\Components\Select::make('organization')
                            ->label('سازمان')
                            ->required()
                            ->searchable()
                            ->options([
                                'jihad' => 'جهاد کشاورزی',
                                'sanat' => 'صنعت معدن و تجارت',
                                'shilat' => 'سازمان شیلات',
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

                        Forms\Components\TextInput::make('city')
                            ->label('شهرستان')
                            ->required(),

                        Forms\Components\TextInput::make('address')
                            ->label('آدرس')
                            ->nullable(),

                        Forms\Components\DatePicker::make('cooperation_start_date')
                            ->label('تاریخ شروع همکاری')
                            ->required()
                            ->default(now()),

                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                TextColumn::make('national_id')
                    ->label('کد ملی')
                    ->searchable()
                    ->toggleable(),

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
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('cooperation_start_date')
                    ->label('شروع همکاری')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->sortable(),

                TextColumn::make('installed_count')
                    ->label('تعداد نصب')
                    ->getStateUsing(fn (User $record) =>
                        Registration::where('installer_id', $record->id)
                            ->where('status', 'installed')
                            ->count()
                    )
                    ->badge()
                    ->color('success'),

                TextColumn::make('pending_install_count')
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

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('فعال')
                    ->falseLabel('غیرفعال'),
            ])
            ->actions([
                Tables\Actions\Action::make('assign_customers')
                    ->label('انتقال دستگاه')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (User $record) => $record->is_active)
                    ->form(function (User $record) {
                        return [
                            Forms\Components\CheckboxList::make('registration_ids')
                                ->label('مشتریان آماده نصب')
                                ->options(function () use ($record) {
                                    return Registration::where('status', 'device_assigned')
                                        ->whereNull('installer_id')
                                        ->when($record->province, fn ($q) => $q->where('province', $record->province))
                                        ->when($record->city, fn ($q) => $q->where('city', $record->city))
                                        ->get()
                                        ->mapWithKeys(function ($reg) {
                                            $device = $reg->assignedDevice;
                                            $deviceInfo = $device ? $device->serial_number : 'بدون دستگاه';
                                            return [
                                                $reg->id => "{$reg->full_name} | {$reg->phone} | دستگاه: {$deviceInfo}"
                                            ];
                                        });
                                })
                                ->required()
                                ->helperText('مشتریان هم‌استان و هم‌شهرستان با نصاب نمایش داده می‌شوند')
                                ->columns(1),
                        ];
                    })
                    ->action(function (User $record, array $data) {
                        $count = 0;
                        foreach ($data['registration_ids'] as $regId) {
                            Registration::where('id', $regId)->update([
                                'installer_id' => $record->id,
                                'status' => 'ready_for_installation',
                                'installation_scheduled_at' => now(),
                            ]);
                            $count++;
                        }

                        Notification::make()
                            ->success()
                            ->title("{$count} مشتری به {$record->name} انتقال داده شد")
                            ->send();
                    }),

                Tables\Actions\Action::make('list_installed')
                    ->label('نصب شده‌ها')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->modalHeading(fn (User $record) => 'دستگاه‌های نصب شده - ' . $record->name)
                    ->modalContent(function (User $record) {
                        $registrations = Registration::where('installer_id', $record->id)
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
                        $registrations = Registration::where('installer_id', $record->id)
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
                    ->modalCancelActionLabel('بستن')
                    ->extraModalFooterActions(function (User $record) {
                        $registrations = Registration::where('installer_id', $record->id)
                            ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                            ->get();

                        return $registrations->map(function ($reg) {
                            return Tables\Actions\Action::make('remove_' . $reg->id)
                                ->label("حذف {$reg->full_name}")
                                ->color('danger')
                                ->requiresConfirmation()
                                ->action(function () use ($reg) {
                                    $reg->update(['installer_id' => null]);

                                    Notification::make()
                                        ->warning()
                                        ->title("{$reg->full_name} از نصاب حذف شد")
                                        ->send();
                                });
                        })->toArray();
                    }),

                Tables\Actions\Action::make('remove_customer')
                    ->label('حذف مشتری از نصاب')
                    ->icon('heroicon-o-user-minus')
                    ->color('danger')
                    ->visible(fn (User $record) => 
                        Registration::where('installer_id', $record->id)
                            ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                            ->count() > 0
                    )
                    ->form(function (User $record) {
                        return [
                            Forms\Components\CheckboxList::make('registration_ids')
                                ->label('مشتریانی که می‌خواهید حذف کنید')
                                ->options(
                                    Registration::where('installer_id', $record->id)
                                        ->whereIn('status', ['device_assigned', 'ready_for_installation'])
                                        ->get()
                                        ->mapWithKeys(fn ($reg) => [
                                            $reg->id => "{$reg->full_name} | {$reg->phone} | دستگاه: " . ($reg->assignedDevice?->serial_number ?? '—')
                                        ])
                                )
                                ->required()
                                ->columns(1),
                        ];
                    })
                    ->action(function (User $record, array $data) {
                        $count = 0;
                        foreach ($data['registration_ids'] as $regId) {
                            Registration::where('id', $regId)->update([
                                'installer_id' => null,
                            ]);
                            $count++;
                        }

                        Notification::make()
                            ->warning()
                            ->title("{$count} مشتری از {$record->name} حذف شد")
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('هیچ نصابی ثبت نشده')
            ->emptyStateDescription('برای شروع، نصاب جدید اضافه کنید')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstallers::route('/'),
            'create' => Pages\CreateInstaller::route('/create'),
            'edit' => Pages\EditInstaller::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return User::where('operator_tag', 'نصاب')->where('is_active', true)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}