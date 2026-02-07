<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ثبت‌نام موفق</title>
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
        .success-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #16a085 0%, #0f9b6b 100%);
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
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
        .btn {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            display: inline-block;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="success-card">
    <div class="success-icon">✅</div>
    <h1>ثبت‌نام با موفقیت انجام شد!</h1>
    <p>
        درخواست شما با موفقیت ثبت شد و در حال بررسی توسط کارشناسان است.
        <br>
        نتیجه بررسی به زودی از طریق پیامک به شماره شما اطلاع‌رسانی خواهد شد.
    </p>
    <a href="{{ route('register') }}" class="btn">ثبت‌نام جدید</a>
</div>
</body>
</html>
