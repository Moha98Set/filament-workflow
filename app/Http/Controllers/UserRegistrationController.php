<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserRegistrationController extends Controller
{
    /**
     * نمایش فرم ثبت‌نام
     */
    public function showRegistrationForm()
    {
        return view('auth.user-register');
    }

    /**
     * ثبت کاربر جدید
     */
    public function register(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'نام الزامی است',
            'email.required' => 'ایمیل الزامی است',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است',
            'password.required' => 'رمز عبور الزامی است',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
        ]);

        // ایجاد کاربر با وضعیت pending
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending', // منتظر تایید
        ]);

        // انتقال به صفحه موفقیت
        return redirect()->route('user.register.pending')
            ->with('success', 'ثبت‌نام شما با موفقیت انجام شد. لطفاً منتظر تایید مدیر باشید.');
    }

    /**
     * صفحه انتظار برای تایید
     */
    public function pendingPage()
    {
        return view('auth.registration-pending');
    }
}
