<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ایجاد نقش‌ها (اگر از قبل وجود ندارند)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        // ایجاد کاربر سوپر ادمین
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'], // اگر کاربری با این ایمیل بود، دوباره نساز
            [
                'name' => 'Super Admin',
                'password' => bcrypt('12345678'), // رمز عبور
            ]
        );

        // اختصاص نقش به کاربر
        $user->assignRole($superAdminRole);
        
        $this->command->info('Default roles and Super Admin created successfully!');
    }
}