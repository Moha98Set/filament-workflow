<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>منتظر تایید</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .pending-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        h1 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.1rem;
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        .info-box {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-box h3 {
            color: #92400e;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        .info-box p {
            color: #78350f;
            margin: 0;
            font-size: 0.95rem;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="pending-card">
    <div class="icon">⏳</div>
    <h1>ثبت‌نام شما انجام شد!</h1>
    <p>
        درخواست شما با موفقیت ثبت شد و در حال بررسی توسط مدیر سیستم است.
    </p>

    <div class="info-box">
        <h3>مراحل بعدی:</h3>
        <p>
            ✅ ثبت‌نام انجام شد<br>
            ⏳ منتظر تایید مدیر<br>
            🔔 پس از تایید، از طریق ایمیل مطلع خواهید شد
        </p>
    </div>

    <p style="font-size: 0.9rem; color: #718096;">
        لطفاً صبور باشید. این فرایند ممکن است تا 24 ساعت طول بکشد.
    </p>

    <a href="{{ route('login') }}" class="btn">بازگشت به صفحه ورود</a>
</div>
</body>
</html>
