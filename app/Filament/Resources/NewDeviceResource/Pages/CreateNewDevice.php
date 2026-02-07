<?php

/**
 * آدرس فایل: app/Filament/Resources/NewDeviceResource/Pages/CreateNewDevice.php
 */

namespace App\Filament\Resources\NewDeviceResource\Pages;

use App\Filament\Resources\NewDeviceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateNewDevice extends CreateRecord
{
    protected static string $resource = NewDeviceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // اضافه کردن اطلاعات اپراتور به صورت خودکار
        $user = Auth::user();

        $data['user_id'] = $user->id;
        $data['operator_name'] = $user->name;

        return static::getModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'دستگاه جدید با موفقیت ثبت شد';
    }
}
