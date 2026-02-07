<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user, string $ability)
    {
        if ($user->hasRole('super_admin', 'admin')) {
            return true;
        }
    }
    /**
     * چه کسانی لیست کاربران را ببینند؟
     * سوپرادمین و ادمین
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * چه کسی میتواند جزئیات یک کاربر را ببیند؟
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * چه کسی می‌تواند کاربر جدید بسازد؟
     * سوپرادمین (برای ساخت ادمین) و ادمین (برای ساخت اپراتور)
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * چه کسی می‌تواند ویرایش کند؟
     */
    public function update(User $user, User $model): bool
    {
        // سوپر ادمین همه را ویرایش می‌کند
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // ادمین فقط کسانی را ویرایش می‌کند که سوپرادمین نباشند
        if ($user->hasRole('admin')) {
            return ! $model->hasRole('super_admin');
        }

        return false;
    }

    /**
     * چه کسی می‌تواند حذف کند؟
     */
    public function delete(User $user, User $model): bool
    {
        // فقط سوپرادمین اجازه حذف دارد
        return $user->hasRole('super_admin');
    }
}