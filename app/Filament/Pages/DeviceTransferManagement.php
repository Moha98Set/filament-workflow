<?php

namespace App\Filament\Pages;

use App\Models\Device;
use App\Models\Registration;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class DeviceTransferManagement extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'مدیریت';
    protected static ?string $navigationLabel = 'مدیریت جابجایی‌ها';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.device-transfer-management';
    protected static ?string $title = 'مدیریت جابجایی‌ها';

    public ?string $transfer_type = null;

    // حالت ۱: swap
    public ?string $swap_from_id = null;
    public ?string $swap_to_id = null;

    // حالت ۲: transfer
    public ?string $transfer_from_id = null;
    public ?string $transfer_to_id = null;

    public ?string $admin_note = null;

    public bool $showResult = false;
    public string $resultMessage = '';
    public string $resultType = '';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Section::make('نوع جابجایی')
                ->schema([
                    Forms\Components\Select::make('transfer_type')
                        ->label('نوع عملیات')
                        ->required()
                        ->options([
                            'swap' => '🔄 جابجایی متقابل — هر دو دستگاه دارن',
                            'transfer' => '➡️ انتقال دستگاه — از دستگاه‌دار به بدون دستگاه',
                        ])
                        ->live()
                        ->native(false)
                        ->columnSpanFull(),
                ]),

            // حالت ۱: جابجایی متقابل
            Forms\Components\Section::make('جابجایی متقابل')
                ->description('دو متقاضی که هر دو دستگاه دارن، دستگاه‌هاشون جابجا میشه')
                ->schema([
                    Forms\Components\Select::make('swap_from_id')
                        ->label('متقاضی اول')
                        ->searchable()
                        ->required()
                        ->options(fn () => $this->getDeviceHolders())
                        ->native(false)
                        ->live(),

                    Forms\Components\Select::make('swap_to_id')
                        ->label('متقاضی دوم')
                        ->searchable()
                        ->required()
                        ->options(fn () => $this->getDeviceHolders($this->swap_from_id))
                        ->native(false),
                ])
                ->columns(2)
                ->visible(fn () => $this->transfer_type === 'swap'),

            // حالت ۲: انتقال دستگاه
            Forms\Components\Section::make('انتقال دستگاه')
                ->description('دستگاه از متقاضی دستگاه‌دار به متقاضی بدون دستگاه. متقاضی اول به انتظار اختصاص برمیگرده.')
                ->schema([
                    Forms\Components\Select::make('transfer_from_id')
                        ->label('از متقاضی (دستگاه‌دار)')
                        ->searchable()
                        ->required()
                        ->options(fn () => $this->getDeviceHolders())
                        ->native(false),

                    Forms\Components\Select::make('transfer_to_id')
                        ->label('به متقاضی (بدون دستگاه)')
                        ->searchable()
                        ->required()
                        ->options(fn () => $this->getWaitingCustomers())
                        ->native(false),
                ])
                ->columns(2)
                ->visible(fn () => $this->transfer_type === 'transfer'),

            Forms\Components\Section::make('')
                ->schema([
                    Forms\Components\Textarea::make('admin_note')
                        ->label('یادداشت ادمین')
                        ->rows(2)
                        ->placeholder('توضیحات...'),
                ])
                ->visible(fn () => $this->transfer_type !== null),
        ]);
    }

    // متقاضی‌های دستگاه‌دار
    private function getDeviceHolders(?string $excludeId = null): array
    {
        return Registration::whereIn('status', ['device_assigned', 'ready_for_installation', 'installed'])
            ->whereNotNull('assigned_device_id')
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->get()
            ->mapWithKeys(fn ($reg) => [
                $reg->id => "👤 {$reg->full_name} | 📱 {$reg->assignedDevice?->serial_number} | 📞 {$reg->phone} | 🏙️ {$reg->city}"
            ])
            ->toArray();
    }

    // متقاضی‌های بدون دستگاه (تأیید مالی شده)
    private function getWaitingCustomers(): array
    {
        return Registration::where('status', 'financial_approved')
            ->whereNull('assigned_device_id')
            ->get()
            ->mapWithKeys(fn ($reg) => [
                $reg->id => "👤 {$reg->full_name} | 📞 {$reg->phone} | 🏙️ {$reg->city}"
            ])
            ->toArray();
    }

    // متقاضی‌های با دستگاه معیوب
    private function getFaultyDeviceHolders(): array
    {
        return Registration::whereIn('status', ['device_assigned', 'ready_for_installation'])
            ->whereNotNull('assigned_device_id')
            ->whereHas('assignedDevice', fn ($q) => $q->where('status', 'faulty'))
            ->get()
            ->mapWithKeys(fn ($reg) => [
                $reg->id => "🔧 {$reg->full_name} | 📱 {$reg->assignedDevice?->serial_number} (معیوب) | 📞 {$reg->phone}"
            ])
            ->toArray();
    }

    // متقاضی‌های در دست نصاب
    private function getInstallerPendingCustomers(): array
    {
        return Registration::whereIn('status', ['device_assigned', 'ready_for_installation'])
            ->whereNotNull('assigned_device_id')
            ->whereNotNull('installer_id')
            ->get()
            ->mapWithKeys(function ($reg) {
                $installer = User::find($reg->installer_id);
                return [
                    $reg->id => "👤 {$reg->full_name} | 📱 {$reg->assignedDevice?->serial_number} | 🔧 نصاب: " . ($installer?->name ?? '—') . " | 🏙️ {$reg->city}"
                ];
            })
            ->toArray();
    }

    public function executeTransfer(): void
    {
        if (!$this->transfer_type) {
            Notification::make()->danger()->title('نوع جابجایی را انتخاب کنید')->send();
            return;
        }

        $note = $this->admin_note ?? '';

        // حالت ۱: جابجایی متقابل
        if ($this->transfer_type === 'swap') {
            $fromReg = Registration::find($this->swap_from_id);
            $toReg = Registration::find($this->swap_to_id);

            if (!$fromReg || !$toReg) { $this->notFound(); return; }

            $fromDevice = $fromReg->assignedDevice;
            $toDevice = $toReg->assignedDevice;

            $fromReg->update(['assigned_device_id' => $toDevice->id, 'installation_note' => "جابجایی متقابل با {$toReg->full_name} | {$note}"]);
            $toReg->update(['assigned_device_id' => $fromDevice->id]);
            $fromDevice->update(['assigned_to_registration_id' => $toReg->id]);
            $toDevice->update(['assigned_to_registration_id' => $fromReg->id]);

            $this->showSuccess("جابجایی متقابل انجام شد: {$fromReg->full_name} ({$fromDevice->serial_number}) ↔ {$toReg->full_name} ({$toDevice->serial_number})");

        // حالت ۲: انتقال دستگاه
        } elseif ($this->transfer_type === 'transfer') {
            $fromReg = Registration::find($this->transfer_from_id);
            $toReg = Registration::find($this->transfer_to_id);

            if (!$fromReg || !$toReg) { $this->notFound(); return; }

            $device = $fromReg->assignedDevice;

            $toReg->update([
                'assigned_device_id' => $device->id,
                'device_assigned_by' => auth()->id(),
                'device_assigned_at' => now(),
                'status' => 'device_assigned',
            ]);
            $device->update(['assigned_to_registration_id' => $toReg->id]);

            $this->resetCustomer($fromReg, "انتقال دستگاه به {$toReg->full_name} | {$note}");

            $this->showSuccess("دستگاه {$device->serial_number} از {$fromReg->full_name} به {$toReg->full_name} منتقل شد. {$fromReg->full_name} به انتظار اختصاص برگشت.");

        } 
    }

    private function resetCustomer(Registration $reg, string $note): void
    {
        $reg->update([
            'assigned_device_id' => null,
            'device_assigned_by' => null,
            'device_assigned_at' => null,
            'installer_id' => null,
            'sim_activated' => false,
            'device_tested' => false,
            'preparation_approved_by' => null,
            'preparation_approved_at' => null,
            'installation_completed_at' => null,
            'status' => 'financial_approved',
            'installation_note' => $note,
        ]);
    }

    private function showSuccess(string $message): void
    {
        $this->showResult = true;
        $this->resultMessage = $message;
        $this->resultType = 'success';
        Notification::make()->success()->title('عملیات انجام شد')->body($message)->send();
    }

    private function notFound(): void
    {
        Notification::make()->danger()->title('متقاضی پیدا نشد')->send();
    }
}