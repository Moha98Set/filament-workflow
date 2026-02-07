<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ثبت اطلاعات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --success: #10b981;
            --error: #ef4444;
            --bg-start: #faf5ff;
            --bg-end: #f0f9ff;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Vazirmatn', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -20%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.12) 0%, transparent 70%);
            animation: float 25s ease-in-out infinite reverse;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        .container {
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
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

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        .subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            animation: alertSlide 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes alertSlide {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #991b1b;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #065f46;
        }

        .alert ul {
            list-style: none;
            padding: 0;
        }

        .alert li {
            padding: 0.35rem 0;
            padding-right: 1.5rem;
            position: relative;
        }

        .alert-error li::before {
            content: '×';
            position: absolute;
            right: 0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .alert-success::before {
            content: '✓';
            display: inline-block;
            margin-left: 0.5rem;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease-out both;
        }

        .form-group:nth-child(1) { animation-delay: 0.4s; }
        .form-group:nth-child(2) { animation-delay: 0.5s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        .form-group:nth-child(4) { animation-delay: 0.7s; }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.875rem 1.125rem;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1rem;
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid var(--border);
            border-radius: 12px;
            outline: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        input:focus {
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), var(--shadow-md);
            transform: translateY(-2px);
        }

        input::placeholder {
            color: #94a3b8;
        }

        input[type="date"] {
            color: var(--text-primary);
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: opacity(0.6);
            transition: filter 0.2s;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            filter: opacity(1);
        }

        button {
            width: 100%;
            padding: 1rem;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 0.5rem;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            animation: fadeIn 0.8s ease-out 0.8s both;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        button:hover::before {
            opacity: 1;
        }

        button:active {
            transform: translateY(0);
        }

        button span {
            position: relative;
            z-index: 1;
        }

        .decorative-element {
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.15;
            pointer-events: none;
        }

        .decorative-element-1 {
            top: -20px;
            left: -20px;
            background: var(--primary);
            animation: pulse 4s ease-in-out infinite;
        }

        .decorative-element-2 {
            bottom: -30px;
            right: -30px;
            background: var(--secondary);
            animation: pulse 5s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.15; }
            50% { transform: scale(1.3); opacity: 0.25; }
        }

        @media (max-width: 640px) {
            .card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
            }

            h2 {
                font-size: 1.75rem;
            }

            body {
                padding: 1rem;
            }
        }

        /* Persian number styling */
        input[lang="fa-IR"] {
            direction: rtl;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="decorative-element decorative-element-1"></div>
    <div class="decorative-element decorative-element-2"></div>

    <div class="card">
        <h2>فرم ثبت اطلاعات</h2>
        <p class="subtitle">لطفاً اطلاعات خود را با دقت وارد نمایید</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('client.store') }}">
            @csrf

            <div class="form-group">
                <label for="first_name">نام</label>
                <input type="text" id="first_name" name="first_name" placeholder="نام خود را وارد کنید" required value="{{ old('first_name') }}">
            </div>

            <div class="form-group">
                <label for="last_name">نام خانوادگی</label>
                <input type="text" id="last_name" name="last_name" placeholder="نام خانوادگی خود را وارد کنید" required value="{{ old('last_name') }}">
            </div>

            <div class="form-group">
                <label for="phone_number">شماره تماس</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="۰۹۱۲۳۴۵۶۷۸۹" required value="{{ old('phone_number') }}" dir="ltr" style="text-align: right;">
            </div>

            <div class="form-group">
                <label for="birth_date">تاریخ تولد</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
            </div>

            <button type="submit">
                <span>ثبت اطلاعات</span>
            </button>
        </form>
    </div>
</div>

<script>
    // Add subtle hover effect to inputs
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateX(-2px)';
            this.parentElement.style.transition = 'transform 0.3s cubic-bezier(0.16, 1, 0.3, 1)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateX(0)';
        });
    });

    // Add ripple effect to button
    document.querySelector('button').addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                left: ${x}px;
                top: ${y}px;
                pointer-events: none;
                animation: ripple 0.6s ease-out;
            `;

        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });

    const style = document.createElement('style');
    style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
    document.head.appendChild(style);
</script>
</body>
</html>
