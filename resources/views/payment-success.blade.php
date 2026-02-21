<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>پرداخت موفق</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Vazirmatn', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: white; border-radius: 28px; padding: 3rem; max-width: 500px; width: 100%; text-align: center; box-shadow: 0 25px 80px rgba(0,0,0,0.08); position: relative; overflow: hidden; }
        .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #16a085, #22c55e); }
        .icon { font-size: 64px; margin-bottom: 20px; animation: popIn 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes popIn { from { transform: scale(0); } to { transform: scale(1); } }
        h1 { font-size: 1.5rem; color: #166534; margin-bottom: 24px; }
        .info-box { background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 16px; padding: 20px; margin-bottom: 24px; text-align: right; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #dcfce7; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: #64748b; }
        .info-row .value { color: #166534; font-weight: 700; }
        .btn { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #16a085, #0f9b6b); color: white; text-decoration: none; border-radius: 14px; font-weight: 700; font-size: 14px; transition: all 0.3s; }
        .btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">✅</div>
    <h1>پرداخت با موفقیت انجام شد</h1>
    <div class="info-box">
        <div class="info-row"><span class="label">شماره پیگیری</span><span class="value">{{ session('ref_number', '—') }}</span></div>
        <div class="info-row"><span class="label">مبلغ پرداختی</span><span class="value">{{ number_format(session('amount', 0) / 10) }} تومان</span></div>
    </div>
    <a href="{{ route('client.register.success') }}" class="btn">ادامه ثبت‌نام</a>
</div>
</body>
</html>