<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceReportResource\Pages;
use App\Filament\Resources\DeviceReportResource\RelationManagers;
use App\Models\DeviceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceReportResource extends Resource
{
    protected static ?string $model = DeviceReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'گزارش‌ها';

    protected static ?string $navigationLabel = 'دستگاه‌ها';
    
    protected static ?string $modelLabel = 'گزارش دستگاه';
    
    protected static ?string $pluralModelLabel = 'گزارش دستگاه‌ها';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }
    
    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeviceReports::route('/'),
            'create' => Pages\CreateDeviceReport::route('/create'),
            'edit' => Pages\EditDeviceReport::route('/{record}/edit'),
        ];
    }
}
