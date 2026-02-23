<?php

namespace App\Filament\Pages;

use App\Models\Device;
use App\Models\ActivityLog;
use App\Models\Registration;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class AutoAssignDevices extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'مدیریت';
    protected static ?string $navigationLabel = 'اختصاص خودکار';
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.auto-assign-devices';

    public ?string $organization = null;
    public ?string $province = null;
    public ?string $city = null;
    public ?string $date_from = null;
    public ?string $date_to = null;
    public ?int $assign_count = null;

    public int $availableDevices = 0;
    public int $waitingCustomers = 0;
    public bool $showStats = false;
    public bool $showResult = false;
    public int $assignedCount = 0;

    public array $assignedList = [];

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('فیلترها')
                    ->schema([
                        Forms\Components\Select::make('organization')
                            ->label('سازمان')
                            ->options([
                                'jihad' => 'جهاد کشاورزی',
                                'sanat' => 'صنعت معدن و تجارت',
                                'shilat' => 'سازمان شیلات',
                            ])
                            ->searchable()
                            ->placeholder('همه سازمان‌ها'),

                        Forms\Components\Select::make('province')
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
                            ])
                            ->searchable()
                            ->placeholder('همه استان‌ها'),

                        Forms\Components\TextInput::make('city')
                            ->label('شهرستان')
                            ->placeholder('همه شهرستان‌ها'),

                        Forms\Components\DatePicker::make('date_from')
                            ->label('از تاریخ'),

                        Forms\Components\DatePicker::make('date_to')
                            ->label('تا تاریخ'),
                    ])
                    ->columns(3),
            ]);
    }

    public function calculateStats(): void
    {
        $this->availableDevices = Device::where('status', 'available')
            ->where('has_sim', true)
            ->count();

        $query = Registration::where('status', 'financial_approved')
            ->whereNull('assigned_device_id');

        if ($this->organization) {
            $query->where('organization', $this->organization);
        }
        if ($this->province) {
            $query->where('province', $this->province);
        }
        if ($this->city) {
            $query->where('city', $this->city);
        }
        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }
        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        $this->waitingCustomers = $query->count();
        $this->showStats = true;
        $this->showResult = false;
    }

    public function executeAssign(): void
    {
        if (!$this->assign_count || $this->assign_count <= 0) {
            Notification::make()
                ->danger()
                ->title('لطفاً تعداد اختصاص را وارد کنید')
                ->send();
            return;
        }

        $devices = Device::where('status', 'available')
            ->where('has_sim', true)
            ->limit($this->assign_count)
            ->get();

        if ($devices->isEmpty()) {
            Notification::make()
                ->danger()
                ->title('دستگاه موجود سیمکارت‌داری وجود ندارد')
                ->send();
            return;
        }

        $query = Registration::where('status', 'financial_approved')
            ->whereNull('assigned_device_id');

        if ($this->organization) {
            $query->where('organization', $this->organization);
        }
        if ($this->province) {
            $query->where('province', $this->province);
        }
        if ($this->city) {
            $query->where('city', $this->city);
        }
        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }
        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        $customers = $query->orderBy('created_at', 'asc')
            ->limit($this->assign_count)
            ->get();

        if ($customers->isEmpty()) {
            Notification::make()
                ->danger()
                ->title('متقاضی‌ای با این فیلترها وجود ندارد')
                ->send();
            return;
        }

        $count = 0;
        $maxAssign = min($devices->count(), $customers->count());

        for ($i = 0; $i < $maxAssign; $i++) {
            $device = $devices[$i];
            $customer = $customers[$i];

            $customer->update([
                'assigned_device_id' => $device->id,
                'device_assigned_by' => auth()->id(),
                'device_assigned_at' => now(),
                'status' => 'device_assigned',
            ]);

            $device->update([
                'status' => 'assigned',
                'assigned_to_registration_id' => $customer->id,
            ]);

            $count++;
            $this->assignedList[] = [
                'full_name' => $customer->full_name,
                'phone' => $customer->phone,
                'city' => $customer->city,
                'serial_number' => $device->serial_number,
                'sim_number' => $device->sim_number ?? '—',
            ];
        }
        
        $this->assignedCount = $count;
        $this->showResult = true;

        ActivityLog::log('auto_assign', "اختصاص خودکار {$count} دستگاه توسط " . auth()->user()->name, null, ['count' => $count]);

        $this->calculateStats();

        Notification::make()
            ->success()
            ->title("✅ {$count} دستگاه با موفقیت اختصاص داده شد")
            ->send();
    }
}