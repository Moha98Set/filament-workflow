<?php

use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| صفحه اصلی (Homepage)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| ثبت‌نام عمومی کاربران (User Registration)
|--------------------------------------------------------------------------
*/
Route::get('/user/register', [UserRegistrationController::class, 'showRegistrationForm'])
    ->name('user.register');

Route::post('/user/register', [UserRegistrationController::class, 'register'])
    ->name('user.register.submit');

Route::get('/user/pending', [UserRegistrationController::class, 'pendingPage'])
    ->name('user.register.pending');

/*
|--------------------------------------------------------------------------
| ثبت‌نام مشتریان (Client Registration - Public)
|--------------------------------------------------------------------------
*/

// صفحه انتخاب استان
Route::get('/client-register', function () {
    return view('province-selection');
})->name('client.register');

// صفحه فرم ثبت‌نام
Route::get('/register-form', function () {
    return view('registration-form');
})->name('register.form');

// ثبت درخواست
Route::post('/client-register/submit', [RegistrationController::class, 'store'])
    ->name('client.register.submit');

// صفحه موفقیت (نام اصلاح شد)
Route::get('/register/success', function () {
    return view('registration-success');
})->name('client.register.success');

/*
|--------------------------------------------------------------------------
| Route های تست (فقط Development)
|--------------------------------------------------------------------------
*/
Route::get('/test-auth', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $panel = \Filament\Facades\Filament::getPanel('admin');
        
        return 'Logged in: YES<br>' .
               'Email: ' . $user->email . '<br>' .
               'Status: ' . $user->status . '<br>' .
               'Can access panel: ' . ($user->canAccessPanel($panel) ? 'YES ✅' : 'NO ❌');
    }
    return 'Not logged in ❌';
})->middleware('web');

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze - حذف شده)
|--------------------------------------------------------------------------
*/
// require __DIR__.'/auth.php';