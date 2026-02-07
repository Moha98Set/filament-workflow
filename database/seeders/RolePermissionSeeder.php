<?php

/**
 * آدرس فایل: database/seeders/RolePermissionSeeder.php
 *
 * این نسخه به‌روز شده شامل دسترسی‌های دستگاه است
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // پاک کردن cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ایجاد دسترسی‌ها
        $permissions = [
            // دسترسی‌های مشتریان
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',

            // دسترسی‌های دستگاه‌ها (جدید)
            'view_devices',
            'create_devices',
            'edit_devices',
            'delete_devices',

            // دسترسی‌های مدیریتی
            'manage_users',
            'manage_permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ایجاد نقش‌ها
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $operator = Role::firstOrCreate(['name' => 'operator']);

        // دادن همه دسترسی‌ها به سوپرادمین
        $superAdmin->syncPermissions(Permission::all());

        // دادن دسترسی‌های خاص به ادمین
        $admin->syncPermissions([
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'view_devices',
            'create_devices',
            'edit_devices',
            'delete_devices',
            'manage_users',
        ]);

        // اپراتور به صورت پیش‌فرض دسترسی ندارد
        $operator->syncPermissions([]);

        $this->command->info('✅ نقش‌ها و دسترسی‌ها (شامل دستگاه‌ها) با موفقیت ایجاد شدند!');
    }
}
