<?php

/**
 * آدرس فایل: app/Filament/Resources/UserResource/Pages/EditUser.php
 */

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // بارگذاری نقش‌ها و دسترسی‌های فعلی کاربر
        // فقط اولین نقش را نمایش می‌دهیم (چون هر کاربر یک نقش دارد)
        $roles = $this->record->roles->pluck('name')->toArray();
        $data['roles'] = !empty($roles) ? $roles[0] : null;

        $data['permissions'] = $this->record->permissions->pluck('name')->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // اگر رمز عبور خالی است، حذف کن
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        } else if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // به‌روزرسانی نقش‌ها
        if (isset($this->data['roles'])) {
            $roleName = is_array($this->data['roles']) ? $this->data['roles'][0] : $this->data['roles'];
            $this->record->syncRoles([$roleName]);
        }

        // به‌روزرسانی دسترسی‌های خاص
        if (isset($this->data['permissions']) && is_array($this->data['permissions'])) {
            $this->record->syncPermissions($this->data['permissions']);
        } else {
            $this->record->syncPermissions([]);
        }
    }
}
