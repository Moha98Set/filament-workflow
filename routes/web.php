<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ثبت‌نام کاربران
Route::get('/user/register', [UserRegistrationController::class, 'showRegistrationForm'])
    ->name('user.register');

Route::post('/user/register', [UserRegistrationController::class, 'register'])
    ->name('user.register.submit');

Route::get('/user/pending', [UserRegistrationController::class, 'pendingPage'])
    ->name('user.register.pending');

// 1️⃣ صفحه اول: انتخاب استان
Route::get('/client-register', function () {
    return view('province-selection');
})->name('register');

// 2️⃣ صفحه دوم: فرم ثبت‌نام (با پارامترهای استان و سازمان)
Route::get('/register-form', function () {
    return view('registration-form');
})->name('register.form');

Route::post('/client-register/submit', [RegistrationController::class, 'store'])
    ->name('client.register.submit');

// صفحه موفقیت
Route::get('/register/success', function () {
    return view('registration-success');
})->name('register.success');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/Login', [LoginController::class, 'show'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/register-client', function () {
    return view('register-client'); // فایلی که در مرحله بعد می‌سازیم
});

Route::post('/register-client', [ClientRegistrationController::class, 'store'])->name('client.store');

Route::get('/test-auth', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return 'Logged in as: ' . $user->email . '<br>Status: ' . $user->status . '<br>Can access panel: ' . ($user->canAccessPanel(app(\Filament\Panel::class)) ? 'YES' : 'NO');
    }
    return 'Not logged in';
});

require __DIR__.'/auth.php';
