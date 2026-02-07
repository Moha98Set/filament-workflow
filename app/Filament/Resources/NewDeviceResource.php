<?php

/**
 * آدرس فایل: app/Filament/Resources/NewDeviceResource.php
 * (نسخه اصلاح شده - فقط با permission نمایش داده می‌شود)
 */

namespace App\Filament\Resources;

use App\Filament\Resources\NewDeviceResource\Pages;
use App\Models\NewDevice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NewDeviceResource extends Resource
{
    protected static ?string $model = NewDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationLabel = 'ثبت دستگاه جدید';

    protected static ?string $modelLabel = 'دستگاه جدید';

    protected static ?string $pluralModelLabel = 'دستگاه‌های جدید';

    /**
     * بررسی دسترسی به لیست دستگاه‌ها
     * فقط کسانی که permission دارند می‌توانند ببینند
     */
    public static function canViewAny(): bool
    {
        $user = Auth::user();

        // اگر کاربر لاگین نکرده، دسترسی ندارد
        if (!$user) {
            return false;
        }

        // سوپرادمین و ادمین همیشه دسترسی دارند
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // اپراتور فقط با permission خاص
        // اگر permission نداشته باشد، منو نمایش داده نمی‌شود
        return $user->can('view_devices');
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('create_devices');
    }

    public static function canEdit($record): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('edit_devices');
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('delete_devices');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('کد دستگاه')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('کد دستگاه را وارد کنید')
                    ->unique(ignoreRecord: true)
                    ->helperText('کد دستگاه باید یکتا باشد'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('کد دستگاه')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('کد کپی شد')
                    ->copyMessageDuration(1500)
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('operator_name')
                    ->label('اپراتور ثبت‌کننده')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('ایمیل اپراتور')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable()
                    ->since()
                    ->icon('heroicon-m-clock'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاریخ ویرایش')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('از تاریخ'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('تا تاریخ'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn ($query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn ($query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                Tables\Filters\SelectFilter::make('operator')
                    ->label('اپراتور')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('ثبت اولین دستگاه')
                    ->icon('heroicon-m-plus'),
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
            'index' => Pages\ListNewDevices::route('/'),
            'create' => Pages\CreateNewDevice::route('/create'),
            //'view' => Pages\ViewNewDevice::route('/{record}'),
            'edit' => Pages\EditNewDevice::route('/{record}/edit'),
        ];
    }

    /**
     * تعداد رکوردها برای نمایش در Navigation Badge
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * رنگ Badge در Navigation
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}
