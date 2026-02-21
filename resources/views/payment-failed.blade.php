<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>پرداخت ناموفق</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Vazirmatn', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: white; border-radius: 28px; padding: 3rem; max-width: 500px; width: 100%; text-align: center; box-shadow: 0 25px 80px rgba(0,0,0,0.08); position: relative; overflow: hidden; }
        .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #ef4444, #f59e0b); }
        .icon { font-size: 64px; margin-bottom: 20px; }
        h1 { font-size: 1.5rem; color: #991b1b; margin-bottom: 12px; }
        p { font-size: 14px; color: #64748b; line-height: 1.8; margin-bottom: 24px; }
        .error-box { background: #fef2f2; border: 2px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b; font-size: 14px; font-weight: 600; }
        .actions { display: flex; gap: 12px; }
        .btn { flex: 1; padding: 14px; border-radius: 14px; font-weight: 700; font-size: 14px; text-decoration: none; text-align: center; transition: all 0.3s; font-family: 'Vazirmatn', sans-serif; }
        .btn-retry { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .btn-back { background: #f8fafc; color: #475569; border: 2px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">❌</div>
    <h1>پرداخت ناموفق بود</h1>
    @if(session('error'))
    <div class="error-box">{{ session('error') }}</div>
    @endif
    <p>در صورت کسر وجه از حساب شما، مبلغ طی ۷۲ ساعت به حساب شما برمی‌گردد.</p>
    <div class="actions">
        <a href="{{ route('client.register') }}" class="btn btn-retry">تلاش مجدد</a>
        <a href="/" class="btn btn-back">صفحه اصلی</a>
    </div>
</div>
</body>
</html>