<?php


/**
 * آدرس فایل: database/seeders/CleanupAndFixRolesSeeder.php
 *
 * نحوه استفاده:
 * php artisan db:seed --class=CleanupAndFixRolesSeeder
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class CleanupAndFixRolesSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🧹 در حال پاک‌سازی داده‌های قدیمی...');

        // پاک کردن تمام اتصالات نقش‌ها از کاربران
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();

        $this->command->info('✅ داده‌های قدیمی پاک شدند');

        // اختصاص نقش به کاربران موجود بر اساس نام آن‌ها
        $this->command->info('🔄 در حال اختصاص نقش‌ها به کاربران...');

        // کاربر اول را سوپرادمین کن
        $superAdmin = User::find(1);
        if ($superAdmin) {
            $superAdmin->assignRole('super_admin');
            $this->command->info("✅ {$superAdmin->name} → سوپر ادمین");
        }

        // کاربر دوم را ادمین کن (اگر وجود داشته باشد)
        $admin = User::find(2);
        if ($admin) {
            $admin->assignRole('admin');
            $this->command->info("✅ {$admin->name} → ادمین");
        }

        // کاربر سوم را اپراتور کن (اگر وجود داشته باشد)
        $operator = User::find(3);
        if ($operator) {
            $operator->assignRole('operator');
            // به اپراتور فقط دسترسی مشاهده بده
            $operator->givePermissionTo('view_clients');
            $this->command->info("✅ {$operator->name} → اپراتور (با دسترسی مشاهده مشتریان)");
        }

        $this->command->info('');
        $this->command->info('✨ تمام! نقش‌ها با موفقیت اختصاص داده شدند');
        $this->command->info('🔄 لطفاً از سیستم خارج شده و دوباره وارد شوید');
    }
}
