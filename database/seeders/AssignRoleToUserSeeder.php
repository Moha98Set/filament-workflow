<?php

/**
 * آدرس فایل: database/seeders/AssignRoleToUserSeeder.php
 *
 * نحوه استفاده:
 * php artisan db:seed --class=AssignRoleToUserSeeder
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToUserSeeder extends Seeder
{
    public function run()
    {
        // پیدا کردن اولین کاربر (یا کاربری که الان لاگین کرده‌اید)
        $user = User::first();

        // یا می‌توانید با ایمیل خاص پیدا کنید:
        // $user = User::where('email', 'your@email.com')->first();

        if ($user) {
            // حذف نقش‌های قبلی
            $user->syncRoles([]);

            // اختصاص نقش سوپرادمین
            $user->assignRole('super_admin');

            $this->command->info("✅ نقش super_admin به کاربر {$user->name} ({$user->email}) اختصاص داده شد!");
        } else {
            $this->command->error('❌ هیچ کاربری یافت نشد!');
        }
    }
}
