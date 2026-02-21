<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class DevicesWithoutSim extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = DeviceResource::class;

    protected static string $view = 'filament.resources.device-resource.pages.devices-without-sim';
    
    protected static ?string $navigationLabel = 'دستگاه‌های فاقد سیمکارت';
    
    protected static ?string $title = 'دستگاه‌های فاقد سیمکارت';

    public function table(Table $table): Table
    {
        return $table
            ->query(Device::query()->withoutSim())
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('کد دستگاه')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('سریال')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalali($state))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('add_sim')
                    ->label('افزودن سیمکارت')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('sim_number')
                            ->label('شماره سیمکارت')
                            ->tel()
                            ->required()
                            ->placeholder('09xxxxxxxxx'),
                        
                        Forms\Components\TextInput::make('sim_serial')
                            ->label('سریال سیمکارت')
                            ->required()
                            ->placeholder('مثال: 8998xxxxxxxxxx'),
                    ])
                    ->action(function (Device $record, array $data) {
                        $record->update([
                            'sim_number' => $data['sim_number'],
                            'sim_serial' => $data['sim_serial'],
                            'has_sim' => true,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('سیمکارت با موفقیت اضافه شد')
                            ->send();
                    })
                    ->modalWidth('md')
                    ->modalHeading('افزودن اطلاعات سیمکارت')
                    ->modalSubmitActionLabel('ثبت'),
            ])
            ->bulkActions([
                // می‌تونید bulk action برای ثبت دسته‌جمعی اضافه کنید
            ]);
    }
}