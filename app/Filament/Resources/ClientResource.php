<?php

/**
 * آدرس فایل: app/Filament/Resources/ClientResource.php
 */

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'مشتریان';

    /**
     * بررسی دسترسی به لیست مشتریان
     * سوپرادمین و ادمین همیشه دسترسی دارند
     * اپراتور فقط با permission خاص
     */
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        // سوپرادمین و ادمین همیشه دسترسی دارند
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // اپراتور فقط با permission خاص
        return $user->can('view_clients');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('create_clients');
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('edit_clients');
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('delete_clients');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('last_name')
                    ->label('نام خانوادگی')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone_number')
                    ->label('شماره تماس')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\DatePicker::make('birth_date')
                    ->label('تاریخ تولد'),

                Forms\Components\Toggle::make('is_new')
                    ->label('جدید')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('نام خانوادگی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('شماره تماس')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_new')
                    ->label('جدید')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت‌نام')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_new')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('جدید')
                    ->falseLabel('قدیمی'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
