<?php

/**
 * آدرس فایل: database/seeders/AddDevicePermissionsSeeder.php
 *
 * دستور اجرا:
 * php artisan db:seed --class=AddDevicePermissionsSeeder
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddDevicePermissionsSeeder extends Seeder
{
    public function run()
    {
        // پاک کردن cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔄 در حال ایجاد permissions دستگاه‌ها...');

        // ایجاد permissions دستگاه‌ها
        $devicePermissions = [
            'view_devices',
            'create_devices',
            'edit_devices',
            'delete_devices',
        ];

        foreach ($devicePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->command->info("✅ Permission '{$permission}' ایجاد شد");
        }

        // دادن permission به سوپرادمین
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($devicePermissions);
            $this->command->info('✅ Permissions به سوپرادمین داده شد');
        }

        // دادن permission به ادمین
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($devicePermissions);
            $this->command->info('✅ Permissions به ادمین داده شد');
        }

        $this->command->info('');
        $this->command->info('🎉 تمام! حالا می‌توانید به اپراتورها دسترسی بدهید');
    }
}
