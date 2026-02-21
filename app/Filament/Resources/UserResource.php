<?php

/**
 * آدرس فایل: app/Filament/Resources/UserResource.php
 * (نسخه به‌روز شده با گروه‌بندی دسترسی‌ها)
 */

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'مدیریت کاربران';
    
    protected static ?string $navigationGroup = 'تنظیمات';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }
        
        return $user->can('manage_users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات پایه')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('password')
                            ->label('رمز عبور')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('وضعیت و نقش')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'pending' => '⏳ منتظر تایید',
                                'active' => '✅ فعال',
                                'rejected' => '❌ رد شده',
                                'suspended' => '🚫 معلق',
                            ])
                            ->required()
                            ->native(false)
                            ->live(),
                        
                        Forms\Components\Select::make('roles')
                            ->label('نقش')
                            ->options([
                                'super_admin' => 'سوپر ادمین',
                                'admin' => 'ادمین',
                                'operator' => 'اپراتور',
                            ])
                            ->native(false)
                            ->required()
                            ->live()
                            ->visible(fn (Forms\Get $get) => $get('status') === 'active'),
                        
                        Forms\Components\TextInput::make('operator_tag')
                            ->label('تگ اپراتور')
                            ->placeholder('مثال: کارشناس مالی، کارشناس فنی')
                            ->helperText('این تگ فقط برای اپراتورها کاربرد دارد')
                            ->maxLength(100)
                            ->visible(fn (Forms\Get $get) => $get('roles') === 'operator' && $get('status') === 'active'),
                        
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('دلیل رد')
                            ->helperText('لطفاً دلیل رد ثبت‌نام را توضیح دهید')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get) => $get('status') === 'rejected'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('دسترسی‌های خاص')
                    ->schema([
                        // گروه مشتریان
                        Forms\Components\Checkbox::make('access_clients')
                            ->label('🧑‍💼 دسترسی کامل به مشتریان')
                            ->helperText('مشاهده، ایجاد، ویرایش و حذف مشتریان')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $set('permissions.view_clients', true);
                                    $set('permissions.create_clients', true);
                                    $set('permissions.edit_clients', true);
                                    $set('permissions.delete_clients', true);
                                } else {
                                    $set('permissions.view_clients', false);
                                    $set('permissions.create_clients', false);
                                    $set('permissions.edit_clients', false);
                                    $set('permissions.delete_clients', false);
                                }
                            }),
                        
                        // گروه دستگاه‌ها
                        Forms\Components\Checkbox::make('access_devices')
                            ->label('📱 دسترسی کامل به دستگاه‌ها')
                            ->helperText('مشاهده، ثبت، ویرایش و حذف دستگاه‌ها')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $set('permissions.view_devices', true);
                                    $set('permissions.create_devices', true);
                                    $set('permissions.edit_devices', true);
                                    $set('permissions.delete_devices', true);
                                } else {
                                    $set('permissions.view_devices', false);
                                    $set('permissions.create_devices', false);
                                    $set('permissions.edit_devices', false);
                                    $set('permissions.delete_devices', false);
                                }
                            }),
                        
                        // چک‌باکس‌های جزئی (مخفی، فقط برای ذخیره)
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('دسترسی‌های جزئی')
                            ->options([
                                'view_clients' => 'مشاهده مشتریان',
                                'create_clients' => 'ایجاد مشتری',
                                'edit_clients' => 'ویرایش مشتری',
                                'delete_clients' => 'حذف مشتری',
                                'view_devices' => 'مشاهده دستگاه‌ها',
                                'create_devices' => 'ثبت دستگاه جدید',
                                'edit_devices' => 'ویرایش دستگاه',
                                'delete_devices' => 'حذف دستگاه',
                            ])
                            ->columns(2)
                            ->visible(false) // مخفی کردیم چون از چک‌باکس‌های گروهی استفاده می‌کنیم
                            ->dehydrated(true), // ولی باید ذخیره بشه
                    ])
                    ->visible(fn (Forms\Get $get) => $get('roles') === 'operator' && $get('status') === 'active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'منتظر تایید',
                        'active' => 'فعال',
                        'rejected' => 'رد شده',
                        'suspended' => 'معلق',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger' => 'rejected',
                        'secondary' => 'suspended',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'active',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-pause-circle' => 'suspended',
                    ]),
                
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('نقش')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'super_admin' => 'سوپر ادمین',
                        'admin' => 'ادمین',
                        'operator' => 'اپراتور',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'operator' => 'info',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('operator_tag')
                    ->label('تگ')
                    ->badge()
                    ->color('success')
                    ->default('—')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalali($state))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'منتظر تایید',
                        'active' => 'فعال',
                        'rejected' => 'رد شده',
                        'suspended' => 'معلق',
                    ]),
                
                Tables\Filters\SelectFilter::make('role')
                    ->label('نقش')
                    ->options([
                        'super_admin' => 'سوپر ادمین',
                        'admin' => 'ادمین',
                        'operator' => 'اپراتور',
                    ])
                    ->query(function ($query, $state) {
                        if ($state['value'] ?? null) {
                            $query->whereHas('roles', function ($q) use ($state) {
                                $q->where('name', $state['value']);
                            });
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('تایید')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->status === 'pending')
                    ->action(function (User $record) {
                        $record->update([
                            'status' => 'active',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                        
                        Notification::make()
                            ->success()
                            ->title('کاربر تایید شد')
                            ->body("کاربر {$record->name} فعال شد. حالا باید نقش به او اختصاص دهید.")
                            ->send();
                    }),
                
                Tables\Actions\Action::make('reject')
                    ->label('رد')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('دلیل رد')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        
                        Notification::make()
                            ->danger()
                            ->title('کاربر رد شد')
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}