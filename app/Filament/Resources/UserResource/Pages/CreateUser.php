<?php

/**
 * آدرس فایل: app/Filament/Resources/UserResource/Pages/CreateUser.php
 */

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // ایجاد کاربر
        $user = static::getModel()::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // اختصاص نقش (باید نام نقش را بدهیم نه ID)
        if (isset($data['roles'])) {
            // اگر آرایه است، اولین نقش را بگیر
            $roleName = is_array($data['roles']) ? $data['roles'][0] : $data['roles'];
            $user->assignRole($roleName);
        }

        // اختصاص دسترسی‌های خاص (برای اپراتور)
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        return $user;
    }
}
