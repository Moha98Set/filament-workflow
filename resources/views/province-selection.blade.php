<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انتخاب سازمان و استان</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700&display=swap');
        body { 
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .province-btn {
            width: 100%;
            padding: 1.25rem 1.5rem;
            color: white;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }
        
        .province-btn:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }
        
        .province-btn:active {
            transform: translateY(0);
        }
        
        /* Custom SweetAlert Styles */
        .swal2-popup {
            font-family: 'Vazirmatn', sans-serif !important;
            direction: rtl !important;
            border-radius: 24px !important;
            padding: 0 !important;
            width: 600px !important;
            background: #f5f5f5 !important;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 0 !important;
        }

        .swal2-actions {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Warning Icon Animation */
        @keyframes warningPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .warning-icon {
            animation: warningPulse 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen py-16">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block p-4 bg-white/10 backdrop-blur-lg rounded-full mb-6">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">انتخاب سازمان و استان</h1>
            <p class="text-xl text-white/90">لطفاً سازمان و استان خود را انتخاب کنید</p>
        </div>

        <!-- سازمان جهاد کشاورزی -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6 hover:shadow-3xl transition-shadow">
            <h2 class="text-2xl font-bold text-center text-green-700 mb-6 flex items-center justify-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                سازمان جهاد کشاورزی
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <button onclick="selectProvince('استان فارس', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان فارس</button>
                <button onclick="selectProvince('استان بوشهر', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان بوشهر</button>
                <button onclick="selectProvince('استان خوزستان', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان خوزستان</button>
                <button onclick="selectProvince('استان خراسان رضوی', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان خراسان رضوی</button>
                <button onclick="selectProvince('استان زنجان', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان زنجان</button>
                <button onclick="selectProvince('استان هرمزگان', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان هرمزگان</button>
                <button onclick="selectProvince('استان چهارمحال و بختیاری', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان چهارمحال و بختیاری</button>
                <button onclick="selectProvince('استان کهگیلویه و بویراحمد', 'جهاد کشاورزی')" class="province-btn" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">استان کهگیلویه و بویراحمد</button>
            </div>
        </div>

        <!-- سازمان صنعت معدن و تجارت -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6 hover:shadow-3xl transition-shadow">
            <h2 class="text-2xl font-bold text-center text-red-700 mb-6 flex items-center justify-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                سازمان صنعت معدن و تجارت
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <button onclick="selectProvince('استان فارس', 'صنعت معدن و تجارت')" class="province-btn" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">استان فارس</button>
                <button onclick="selectProvince('استان بوشهر', 'صنعت معدن و تجارت')" class="province-btn" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">استان بوشهر</button>
                <button onclick="selectProvince('استان هرمزگان', 'صنعت معدن و تجارت')" class="province-btn" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">استان هرمزگان</button>
                <button onclick="selectProvince('استان کهگیلویه و بویراحمد', 'صنعت معدن و تجارت')" class="province-btn" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">استان کهگیلویه و بویراحمد</button>
            </div>
        </div>

        <!-- سازمان شیلات -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 hover:shadow-3xl transition-shadow">
            <h2 class="text-2xl font-bold text-center text-blue-700 mb-6 flex items-center justify-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                سازمان شیلات
            </h2>
            <div class="grid grid-cols-1 gap-3">
                <button onclick="selectProvince('ایران', 'سازمان شیلات')" class="province-btn" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">سازمان شیلات ایران</button>
            </div>
        </div>

    </div>

    <script>
        function selectProvince(province, organization) {
            let iconColor = '';
            let gradientColor = '';
            
            if (organization === 'جهاد کشاورزی') {
                iconColor = '#059669';
                gradientColor = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            } else if (organization === 'صنعت معدن و تجارت') {
                iconColor = '#dc2626';
                gradientColor = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
            } else if (organization === 'سازمان شیلات') {
                iconColor = '#2563eb';
                gradientColor = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
            }
            
            Swal.fire({
                html: `
                    <div style="background: white; border-radius: 24px; overflow: hidden;">
                        <!-- Warning Icon -->
                        <div style="padding: 40px 40px 0;">
                            <div class="warning-icon" style="width: 100px; height: 100px; margin: 0 auto; background: ${gradientColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                                <svg style="width: 50px; height: 50px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Title -->
                        <div style="padding: 30px 40px 0;">
                            <h2 style="font-size: 26px; font-weight: 700; color: #1f2937; margin: 0; text-align: center;">تأیید ثبت در ${organization}</h2>
                        </div>

                        <!-- Description -->
                        <div style="padding: 20px 40px;">
                            <p style="color: #4b5563; font-size: 15px; line-height: 1.8; text-align: center; margin: 0;">
                                شما در حال ثبت اطلاعات در <strong style="color: ${iconColor};">${organization}</strong> استان <strong style="color: #1f2937;">${province}</strong> هستید. لطفاً فرم را با دقت و صحت کامل تکمیل کنید. با انتخاب دکمه زیر مسئولیت صحت اطلاعات را می‌پذیرید.
                            </p>
                        </div>

                        <!-- Province Display -->
                        <div style="padding: 0 40px 30px;">
                            <div style="background: ${gradientColor}; padding: 18px; border-radius: 16px; text-align: center;">
                                <p style="color: white; font-size: 18px; font-weight: 700; margin: 0;">استان: ${province}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; border-top: 1px solid #e5e7eb;">
                            <button onclick="Swal.close()" style="background: white; color: #6b7280; border: none; padding: 20px; font-size: 17px; font-weight: 600; cursor: pointer; transition: all 0.2s; border-bottom-right-radius: 24px; border-right: 1px solid #e5e7eb;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                                انصراف
                            </button>
                            <button id="confirmBtn" style="background: ${gradientColor}; color: white; border: none; padding: 20px; font-size: 17px; font-weight: 600; cursor: pointer; transition: all 0.2s; border-bottom-left-radius: 24px;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                شروع تکمیل فرم
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: false,
                padding: 0,
                background: 'transparent',
                backdrop: 'rgba(0,0,0,0.4)',
                customClass: {
                    popup: 'swal2-popup'
                },
                didOpen: () => {
                    document.getElementById('confirmBtn').addEventListener('click', () => {
                        localStorage.setItem('selected_province', province);
                        localStorage.setItem('selected_organization', organization);
                        
                        window.location.href = "/register-form?province=" + encodeURIComponent(province) + "&organization=" + encodeURIComponent(organization);
                    });
                }
            });
        }
    </script>
</body>
</html>