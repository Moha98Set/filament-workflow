<?php

namespace App\Filament\Pages;

use App\Models\InstallerRequest;
use App\Models\ActivityLog;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;

class InstallerRequestsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'مدیریت';
    protected static ?string $navigationLabel = 'درخواست‌های نصاب‌ها';
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.installer-requests';
    protected static ?string $title = 'درخواست‌های نصاب‌ها';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = InstallerRequest::where('status', 'pending')->count();
        return $count > 0 ? (string)$count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(InstallerRequest::query()->with(['installer', 'registration', 'device', 'reviewer'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalaliDateTime($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('installer.name')
                    ->label('نصاب')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('registration.full_name')
                    ->label('مشتری')
                    ->searchable(),

                Tables\Columns\TextColumn::make('registration.city')
                    ->label('شهرستان'),

                Tables\Columns\TextColumn::make('device.serial_number')
                    ->label('سریال دستگاه')
                    ->default('—'),

                Tables\Columns\TextColumn::make('type_label')
                    ->label('نوع درخواست')
                    ->badge()
                    ->color(fn (InstallerRequest $record) => match($record->type) {
                        'faulty' => 'danger',
                        'relocation' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('توضیحات')
                    ->limit(40)
                    ->tooltip(fn (InstallerRequest $record) => $record->description),

                Tables\Columns\TextColumn::make('status_label')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (InstallerRequest $record) => match($record->status) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('بررسی توسط')
                    ->default('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => '⏳ در انتظار',
                        'approved' => '✅ تأیید شده',
                        'rejected' => '❌ رد شده',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع')
                    ->options([
                        'faulty' => '⚠️ معیوبی',
                        'relocation' => '🔄 جابجایی',
                    ]),
            ])
            ->actions([
                // تأیید درخواست
                Tables\Actions\Action::make('approve')
                    ->label('تأیید')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (InstallerRequest $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('یادداشت ادمین')
                            ->rows(2),
                    ])
                    ->action(function (InstallerRequest $record, array $data) {
                        $record->update([
                            'status' => 'approved',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);

                        if ($record->type === 'faulty') {
                            // دستگاه معیوب
                            if ($record->device) {
                                $record->device->update(['status' => 'faulty', 'assigned_to_registration_id' => null]);
                            }
                            // مشتری برگرده به انتظار اختصاص
                            $record->registration->update([
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
                            ]);
                        }

                        if ($record->type === 'relocation') {
                            $record->registration->update([
                                'status' => 'relocation_requested',
                            ]);
                        }

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('درخواست تأیید شد')
                            ->send();

                        ActivityLog::log('request_approved', "تأیید درخواست {$record->type_label} — مشتری: {$record->registration->full_name}", $record->registration);

                        // نوتیفیکیشن به نصاب
                        $typeLabel = $record->type === 'faulty' ? 'معیوبی' : 'جابجایی';
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title("درخواست {$typeLabel} تأیید شد")
                            ->body("مشتری: {$record->registration->full_name}")
                            ->icon('heroicon-o-check-circle')
                            ->sendToDatabase($record->installer);
                    }),

                // رد درخواست
                Tables\Actions\Action::make('reject')
                    ->label('رد')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (InstallerRequest $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('دلیل رد')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (InstallerRequest $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'admin_note' => $data['admin_note'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('درخواست رد شد')
                            ->send();

                        ActivityLog::log('request_rejected', "رد درخواست {$record->type_label} — مشتری: {$record->registration->full_name}", $record->registration);

                        // نوتیفیکیشن به نصاب
                        $typeLabel = $record->type === 'faulty' ? 'معیوبی' : 'جابجایی';
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title("درخواست {$typeLabel} رد شد")
                            ->body("دلیل: {$data['admin_note']}")
                            ->icon('heroicon-o-x-circle')
                            ->sendToDatabase($record->installer);
                    }),

                // مشاهده عکس
                Tables\Actions\Action::make('view_photo')
                    ->label('عکس')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->visible(fn (InstallerRequest $record) => !empty($record->photo))
                    ->url(fn (InstallerRequest $record) => asset('storage/' . $record->photo))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}