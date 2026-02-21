<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ثبت‌نام موفق</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .success-card {
            background: white;
            border-radius: 28px;
            padding: 3.5rem 3rem;
            max-width: 560px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 80px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        .success-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #16a085, #22c55e, #16a085);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .icon-wrap {
            width: 110px; height: 110px;
            margin: 0 auto 28px;
            position: relative;
        }
        .icon-circle {
            width: 110px; height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            display: flex; align-items: center; justify-content: center;
            animation: popIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .icon-check {
            width: 56px; height: 56px;
            stroke: #16a085;
            stroke-width: 3;
            fill: none;
            animation: drawCheck 0.8s ease 0.3s both;
        }
        .icon-check path {
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: drawCheck 0.6s ease 0.5s forwards;
        }
        .pulse-ring {
            position: absolute;
            top: 0; left: 0;
            width: 110px; height: 110px;
            border-radius: 50%;
            border: 3px solid #22c55e;
            animation: pulse 2s ease-out infinite;
        }

        @keyframes popIn {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }
        @keyframes drawCheck {
            to { stroke-dashoffset: 0; }
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .success-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: #166534;
            margin-bottom: 16px;
            animation: fadeUp 0.5s ease 0.3s both;
        }
        .success-desc {
            font-size: 15px;
            color: #475569;
            line-height: 2;
            margin-bottom: 28px;
            animation: fadeUp 0.5s ease 0.5s both;
        }

        .info-chips {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
            flex-wrap: wrap;
            animation: fadeUp 0.5s ease 0.6s both;
        }
        .chip {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 600;
        }
        .chip-green { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .chip-blue { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .chip-amber { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 24px 0;
            animation: fadeUp 0.5s ease 0.7s both;
        }

        .next-steps {
            text-align: right;
            margin-bottom: 28px;
            animation: fadeUp 0.5s ease 0.8s both;
        }
        .next-steps-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 14px;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .step-item:last-child { border-bottom: none; }
        .step-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #f0fdf4;
            color: #16a085;
            font-size: 12px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .step-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 12px;
            animation: fadeUp 0.5s ease 0.9s both;
        }
        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            font-family: 'Vazirmatn', sans-serif;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #16a085, #0f9b6b);
            color: white;
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22, 160, 133, 0.4); }
        .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border: 2px solid #e2e8f0;
        }
        .btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .success-card { padding: 2.5rem 1.5rem; }
            .success-title { font-size: 1.4rem; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="success-card">
    <div class="icon-wrap">
        <div class="pulse-ring"></div>
        <div class="icon-circle">
            <svg class="icon-check" viewBox="0 0 52 52">
                <path d="M14 27l8 8 16-16" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <h1 class="success-title">ثبت‌نام شما با موفقیت تکمیل شد</h1>

    <p class="success-desc">
        کاربر گرامی، اطلاعات و قرارداد شما ثبت شد.<br>
        منتظر تماس و پیامک کارشناسان ما باشید.
    </p>

    <div class="info-chips">
        <span class="chip chip-green">✅ اطلاعات ثبت شد</span>
        <span class="chip chip-blue">📋 قرارداد تأیید شد</span>
        <span class="chip chip-amber">⏳ در انتظار بررسی</span>
    </div>

    <div class="divider"></div>

    <div class="next-steps">
        <div class="next-steps-title">مراحل بعدی:</div>
        <div class="step-item">
            <div class="step-num">۱</div>
            <div class="step-text">بررسی اطلاعات و پرداخت توسط کارشناس مالی</div>
        </div>
        <div class="step-item">
            <div class="step-num">۲</div>
            <div class="step-text">اختصاص دستگاه و آماده‌سازی توسط کارشناس فنی</div>
        </div>
        <div class="step-item">
            <div class="step-num">۳</div>
            <div class="step-text">هماهنگی و نصب دستگاه توسط نصاب منطقه شما</div>
        </div>
    </div>

    <div class="actions">
        <a href="{{ route('client.register') }}" class="btn btn-primary">ثبت‌نام متقاضی جدید</a>
        <a href="/" class="btn btn-secondary">بازگشت به صفحه اصلی</a>
    </div>
</div>
</body>
</html>