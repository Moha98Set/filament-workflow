<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ثبت‌نام کاربران</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
        }

        h1 {
            font-size: 2rem;
            color: #2d3748;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #991b1b;
        }

        .alert ul {
            list-style: none;
            padding: 0;
        }

        .alert li {
            padding: 0.25rem 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.875rem 1.125rem;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1rem;
            color: #2d3748;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s;
        }

        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #a0aec0;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .footer-links {
            text-align: center;
            margin-top: 1.5rem;
            color: #718096;
            font-size: 0.9rem;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .register-card {
                padding: 2rem;
            }

            h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
<div class="register-card">
    <div class="logo">👤</div>
    <h1>ثبت‌نام کاربران</h1>
    <p class="subtitle">برای دریافت دسترسی به سیستم ثبت‌نام کنید</p>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('user.register.submit') }}">
        @csrf

        <div class="form-group">
            <label for="name">نام و نام خانوادگی</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="محمد احمدی" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">ایمیل</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور</label>
            <input type="password" id="password" name="password" placeholder="حداقل 8 کاراکتر" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">تکرار رمز عبور</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="تکرار رمز عبور" required>
        </div>

        <button type="submit" class="btn-submit">ثبت‌نام</button>
    </form>

    <div class="footer-links">
        قبلاً ثبت‌نام کرده‌اید؟ <a href="{{ route('login') }}">ورود</a>
    </div>
</div>
</body>
</html>
