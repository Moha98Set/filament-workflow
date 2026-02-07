<?php

/**
 * آدرس فایل: database/seeders/SuperAdminSeeder.php
 * 
 * دستور ایجاد:
 * php artisan make:seeder SuperAdminSeeder
 * 
 * دستور اجرا:
 * php artisan db:seed --class=SuperAdminSeeder
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // 1. ایجاد نقش‌ها
        // ========================================
        
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        echo "✅ نقش‌ها ایجاد شدند\n";

        // ========================================
        // 2. ایجاد دسترسی‌ها
        // ========================================
        
        $permissions = [
            // مشتریان
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            
            // کاربران
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'manage_users',
            
            // دستگاه‌ها
            'view_devices',
            'create_devices',
            'edit_devices',
            'delete_devices',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        echo "✅ دسترسی‌ها ایجاد شدند\n";

        // ========================================
        // 3. اختصاص همه دسترسی‌ها به Super Admin
        // ========================================
        
        $superAdminRole->syncPermissions(Permission::all());

        echo "✅ دسترسی‌ها به Super Admin اختصاص یافتند\n";

        // ========================================
        // 4. ایجاد کاربر Super Admin
        // ========================================
        
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'approved_at' => now(),
            ]
        );

        // اختصاص نقش
        $superAdmin->assignRole('super_admin');

        echo "\n";
        echo "======================================\n";
        echo "✅ Super Admin ایجاد شد!\n";
        echo "======================================\n";
        echo "📧 Email: admin@example.com\n";
        echo "🔑 Password: password123\n";
        echo "======================================\n";
        echo "\n";
        echo "⚠️  حتماً بعد از اولین ورود، رمز عبور را تغییر دهید!\n";
        echo "\n";

        // ========================================
        // 5. ایجاد یک Admin نمونه (اختیاری)
        // ========================================
        
        $admin = User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'status' => 'active',
                'approved_by' => $superAdmin->id,
                'approved_at' => now(),
            ]
        );

        $admin->assignRole('admin');
        $admin->givePermissionTo(['view_clients', 'create_clients', 'edit_clients']);

        echo "✅ یک Admin نمونه نیز ایجاد شد\n";
        echo "📧 Email: admin2@example.com\n";
        echo "🔑 Password: admin123\n";
        echo "\n";

        // ========================================
        // 6. ایجاد یک Operator نمونه (اختیاری)
        // ========================================
        
        $operator = User::firstOrCreate(
            ['email' => 'operator@example.com'],
            [
                'name' => 'اپراتور نمونه',
                'password' => Hash::make('operator123'),
                'status' => 'active',
                'operator_tag' => 'کارشناس مالی',
                'approved_by' => $superAdmin->id,
                'approved_at' => now(),
            ]
        );

        $operator->assignRole('operator');
        $operator->givePermissionTo(['view_clients', 'view_devices']);

        echo "✅ یک Operator نمونه نیز ایجاد شد\n";
        echo "📧 Email: operator@example.com\n";
        echo "🔑 Password: operator123\n";
        echo "🏷️  Tag: کارشناس مالی\n";
        echo "\n";
    }
}