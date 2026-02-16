<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CreateDevice extends CreateRecord
{
    protected static string $resource = DeviceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // آپلود اکسل
        if (!empty($data['excel_file'])) {
            $this->importFromExcel($data['excel_file']);
            $this->halt();
        }

        // ثبت دسته‌جمعی از textarea
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

            Notification::make()
                ->success()
                ->title(count($serials) . ' دستگاه با موفقیت ثبت شد')
                ->send();

            $this->halt();
        }

        $data['created_by'] = auth()->id();

        if (!empty($data['sim_number']) || !empty($data['sim_serial'])) {
            $data['has_sim'] = true;
        }

        return $data;
    }

    protected function importFromExcel(string $filePath): void
    {
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            Notification::make()
                ->danger()
                ->title('فایل پیدا نشد')
                ->send();
            return;
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $count = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            // رد کردن ردیف هدر
            if ($index === 0) continue;

            $serialNumber = trim($row[0] ?? '');
            $simNumber = trim($row[1] ?? '');
            $simSerial = trim($row[2] ?? '');

            if (empty($serialNumber)) continue;

            // چک تکراری
            if (Device::where('serial_number', $serialNumber)->exists()) {
                $errors[] = "ردیف {$index}: سریال {$serialNumber} تکراری است";
                continue;
            }

            Device::create([
                'serial_number' => $serialNumber,
                'type' => 'GPS Tracker',
                'sim_number' => $simNumber ?: null,
                'sim_serial' => $simSerial ?: null,
                'has_sim' => !empty($simNumber),
                'status' => 'available',
                'created_by' => auth()->id(),
            ]);

            $count++;
        }

        // پاک کردن فایل موقت
        @unlink($fullPath);

        if (count($errors) > 0) {
            Notification::make()
                ->warning()
                ->title("{$count} دستگاه ثبت شد، " . count($errors) . " خطا")
                ->body(implode("\n", array_slice($errors, 0, 5)))
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->success()
                ->title("{$count} دستگاه با موفقیت از اکسل ثبت شد")
                ->send();
        }
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