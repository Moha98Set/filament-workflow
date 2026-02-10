<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateDevice extends CreateRecord
{
    protected static string $resource = DeviceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // اگر فیلد serial_numbers وجود داشت، ثبت دسته‌جمعی
        if (isset($data['serial_numbers'])) {
            $serials = array_filter(
                array_map('trim', explode("\n", $data['serial_numbers'])),
                fn($serial) => !empty($serial)
            );

            foreach ($serials as $serial) {
                Device::create([
                    'type' => $data['type'],
                    'serial_number' => $serial,
                    'status' => 'available',
                    'has_sim' => false,
                    'created_by' => auth()->id(),
                ]);
            }

            // جلوگیری از ثبت رکورد اصلی
            $this->halt();
        }

        // برای ویرایش
        $data['created_by'] = auth()->id();
        
        if (!empty($data['sim_number']) || !empty($data['sim_serial'])) {
            $data['has_sim'] = true;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'دستگاه‌ها با موفقیت ثبت شدند';
    }
}