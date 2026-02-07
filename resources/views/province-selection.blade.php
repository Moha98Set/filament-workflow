<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ثبت‌نام سامانه جهاد کشاورزی</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
            background-size: cover;
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            font-size: 3rem;
        }

        h1 {
            font-size: 2.5rem;
            color: white;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .organization-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: fadeInUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .organization-card:nth-child(2) { animation-delay: 0.1s; }
        .organization-card:nth-child(3) { animation-delay: 0.2s; }
        .organization-card:nth-child(4) { animation-delay: 0.3s; }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 3px solid;
            position: relative;
        }

        .card-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 3px;
            background: currentColor;
            border-radius: 3px;
        }

        /* سازمان جهاد کشاورزی - سبز */
        .org-jihad .card-title {
            color: #16a085;
            border-color: #d5f4e6;
        }

        .org-jihad .province-btn {
            background: linear-gradient(135deg, #0f9b6b 0%, #16a085 100%);
        }

        .org-jihad .province-btn:hover {
            background: linear-gradient(135deg, #0d7f59 0%, #138871 100%);
            transform: translateY(-3px);
        }

        /* سازمان صنعت معدن - قرمز تیره */
        .org-industry .card-title {
            color: #9b2c2c;
            border-color: #fed7d7;
        }

        .org-industry .province-btn {
            background: linear-gradient(135deg, #702459 0%, #9b2c2c 100%);
        }

        .org-industry .province-btn:hover {
            background: linear-gradient(135deg, #5a1d47 0%, #7d2323 100%);
            transform: translateY(-3px);
        }

        /* سازمان شیلات - آبی */
        .org-fisheries .card-title {
            color: #1e3a8a;
            border-color: #dbeafe;
        }

        .org-fisheries .province-btn {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .org-fisheries .province-btn:hover {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            transform: translateY(-3px);
        }

        .provinces-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .province-btn {
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-family: 'Vazirmatn', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .province-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .province-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .province-btn:active {
            transform: scale(0.98);
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .organization-card {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.4rem;
            }

            .provinces-grid {
                grid-template-columns: 1fr;
            }
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 3rem;
            opacity: 0.9;
            animation: fadeIn 1s ease-out 0.5s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">🌾</div>
        <h1>سامانه ثبت‌نام</h1>
        <p class="subtitle">لطفاً استان و سازمان مربوطه را انتخاب کنید</p>
    </div>

    <!-- سازمان جهاد کشاورزی -->
    <div class="organization-card org-jihad">
        <h2 class="card-title">سازمان جهاد کشاورزی</h2>
        <div class="provinces-grid">
            <button class="province-btn" onclick="selectProvince('فارس', 'jihad')">استان فارس</button>
            <button class="province-btn" onclick="selectProvince('کهگیلویه و بویراحمد', 'jihad')">استان کهگیلویه و بویراحمد</button>
            <button class="province-btn" onclick="selectProvince('چهارمحال و بختیاری', 'jihad')">استان چهارمحال و بختیاری</button>
            <button class="province-btn" onclick="selectProvince('زنجان', 'jihad')">استان زنجان</button>
            <button class="province-btn" onclick="selectProvince('خراسان رضوی', 'jihad')">استان خراسان رضوی</button>
            <button class="province-btn" onclick="selectProvince('خوزستان', 'jihad')">استان خوزستان</button>
            <button class="province-btn" onclick="selectProvince('هرمزگان', 'jihad')">استان هرمزگان</button>
            <button class="province-btn" onclick="selectProvince('بوشهر', 'jihad')">استان بوشهر</button>
        </div>
    </div>

    <!-- سازمان صنعت معدن و تجارت -->
    <div class="organization-card org-industry">
        <h2 class="card-title">سازمان صنعت معدن و تجارت</h2>
        <div class="provinces-grid">
            <button class="province-btn" onclick="selectProvince('فارس', 'industry')">استان فارس</button>
            <button class="province-btn" onclick="selectProvince('کهگیلویه و بویراحمد', 'industry')">استان کهگیلویه و بویراحمد</button>
            <button class="province-btn" onclick="selectProvince('هرمزگان', 'industry')">استان هرمزگان</button>
            <button class="province-btn" onclick="selectProvince('بوشهر', 'industry')">استان بوشهر</button>
        </div>
    </div>

    <!-- سازمان شیلات -->
    <div class="organization-card org-fisheries">
        <h2 class="card-title">سازمان شیلات</h2>
        <div class="provinces-grid">
            <button class="province-btn" onclick="selectProvince('ایران', 'fisheries')">سازمان شیلات ایران</button>
        </div>
    </div>

    <div class="footer">
        <p>© ۱۴۰۳ - تمامی حقوق محفوظ است</p>
    </div>
</div>

<script>
    function selectProvince(province, organization) {
        // ذخیره اطلاعات در localStorage
        localStorage.setItem('selectedProvince', province);
        localStorage.setItem('selectedOrganization', organization);

        // انتقال به صفحه بعدی (Alert و فرم)
        window.location.href = '{{ route("register.form") }}?province=' + encodeURIComponent(province) + '&org=' + organization;
    }
</script>
</body>
</html>
