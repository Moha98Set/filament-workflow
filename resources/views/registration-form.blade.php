<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فرم ثبت‌نام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Vazirmatn', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 2rem 1rem; }
        .container { max-width: 800px; margin: 0 auto; }
        .form-container { background: white; border-radius: 24px; padding: 3rem; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .form-header { text-align: center; margin-bottom: 2rem; }
        .form-title { font-size: 1.8rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; }
        .form-subtitle { color: #718096; font-size: 0.95rem; }

        /* پروگرس بار */
        .progress-bar { display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; gap: 0; }
        .progress-step { display: flex; flex-direction: column; align-items: center; position: relative; flex: 1; }
        .progress-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; border: 3px solid #e2e8f0; color: #a0aec0; background: white; transition: all 0.4s ease; z-index: 2; }
        .progress-circle.active { border-color: #16a085; color: white; background: #16a085; }
        .progress-circle.done { border-color: #22c55e; color: white; background: #22c55e; }
        .progress-label { font-size: 11px; color: #a0aec0; margin-top: 6px; font-weight: 600; transition: all 0.3s; }
        .progress-label.active { color: #16a085; }
        .progress-label.done { color: #22c55e; }
        .progress-line { flex: 1; height: 3px; background: #e2e8f0; margin: 0 -8px; margin-top: -20px; z-index: 1; transition: all 0.4s; }
        .progress-line.active { background: #22c55e; }

        /* باکس اطلاعات */
        .info-box { background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%); border-right: 4px solid #667eea; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem; }
        .info-item { display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 200px; }
        .info-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .info-icon.org { background: #ede9fe; }
        .info-icon.province { background: #dbeafe; }
        .info-icon svg { width: 24px; height: 24px; }
        .info-label { font-size: 0.85rem; color: #6b7280; font-weight: 500; }
        .info-value { font-size: 1.1rem; font-weight: 700; color: #1e3a5f; }
        .info-divider { width: 1px; height: 40px; background: #cbd5e1; }

        /* استایل‌های فرم */
        .form-section { margin-bottom: 2rem; }
        .section-title { font-size: 1.3rem; font-weight: 700; color: #2d3748; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #16a085; display: inline-block; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
        .form-group { display: flex; flex-direction: column; }
        label { font-size: 0.95rem; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; }
        label .req { color: #e53e3e; }
        input, select, textarea { padding: 0.875rem 1.125rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; font-family: 'Vazirmatn', sans-serif; transition: all 0.3s; background: white; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #16a085; box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1); }
        input::placeholder { color: #a0aec0; }

        .tractor-card { background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 16px; padding: 2rem; margin-bottom: 1.5rem; }
        .tractor-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .tractor-number { font-size: 1.2rem; font-weight: 700; color: #16a085; }
        .btn-remove { padding: 0.5rem 1rem; background: #feb2b2; border: none; border-radius: 8px; color: #742a2a; font-weight: 600; cursor: pointer; font-family: 'Vazirmatn', sans-serif; }
        .btn-add { width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; color: white; font-size: 1rem; font-weight: 600; cursor: pointer; font-family: 'Vazirmatn', sans-serif; }
        .file-box { border: 2px dashed #cbd5e0; border-radius: 12px; padding: 1.5rem; text-align: center; cursor: pointer; background: #f7fafc; }
        .file-box:hover { border-color: #16a085; background: #f0fdf4; }
        .file-box p { color: #4a5568; font-weight: 600; margin-bottom: 0.25rem; }
        .file-box small { color: #a0aec0; }
        .file-info { margin-top: 0.5rem; color: #16a085; font-size: 0.85rem; font-weight: 500; }
        input[type="file"] { display: none; }

        /* دکمه‌ها */
        .form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn-back, .btn-prev { flex: 1; padding: 1.25rem; background: white; border: 2px solid #e2e8f0; color: #4a5568; font-size: 1.1rem; font-weight: 600; border-radius: 12px; cursor: pointer; font-family: 'Vazirmatn', sans-serif; transition: all 0.3s; }
        .btn-back:hover, .btn-prev:hover { background: #f7fafc; border-color: #cbd5e0; }
        .btn-next, .btn-submit { flex: 2; padding: 1.25rem; background: linear-gradient(135deg, #16a085 0%, #0f9b6b 100%); border: none; color: white; font-size: 1.1rem; font-weight: 700; border-radius: 12px; cursor: pointer; font-family: 'Vazirmatn', sans-serif; box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3); transition: all 0.3s; }
        .btn-next:hover, .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22, 160, 133, 0.4); }

        /* مراحل مخفی */
        .step { display: none; animation: fadeIn 0.4s ease; }
        .step.active { display: block; }

        /* پرداخت */
        .payment-options { display: flex; gap: 16px; margin-bottom: 1.5rem; }
        .payment-option { flex: 1; border: 2px solid #e2e8f0; border-radius: 16px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .payment-option:hover { border-color: #16a085; background: #f0fdf4; }
        .payment-option.selected { border-color: #16a085; background: #f0fdf4; box-shadow: 0 0 0 3px rgba(22,160,133,0.15); }
        .payment-option .icon { font-size: 36px; margin-bottom: 8px; }
        .payment-option .title { font-weight: 700; font-size: 15px; color: #1e293b; }
        .payment-option .desc { font-size: 12px; color: #64748b; margin-top: 4px; }

        /* فاکتور */
        .invoice-box { background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 1.5rem; }
        .invoice-header { text-align: center; font-weight: 700; font-size: 16px; color: #1e293b; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; }
        .invoice-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .invoice-row:last-child { border-bottom: none; }
        .invoice-row .label { color: #64748b; font-weight: 500; }
        .invoice-row .value { color: #1e293b; font-weight: 700; }
        .invoice-total { display: flex; justify-content: space-between; padding: 14px 0; margin-top: 8px; border-top: 2px solid #16a085; }
        .invoice-total .label { color: #16a085; font-weight: 700; font-size: 15px; }
        .invoice-total .value { color: #16a085; font-weight: 800; font-size: 18px; }

        /* خلاصه اطلاعات */
        .summary-card { background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 16px; }
        .summary-card .card-title { font-weight: 700; font-size: 14px; color: #16a085; margin-bottom: 12px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .summary-row:last-child { border-bottom: none; }
        .summary-row .s-label { color: #64748b; }
        .summary-row .s-value { color: #1e293b; font-weight: 600; }

        /* قرارداد */
        .contract-box { background: #fffbeb; border: 2px solid #fde68a; border-radius: 16px; padding: 24px; margin-bottom: 1.5rem; max-height: 400px; overflow-y: auto; line-height: 2; font-size: 14px; color: #1e293b; }
        .contract-box h4 { text-align: center; margin-bottom: 16px; font-size: 16px; }

        /* تأیید */
        .confirm-check { display: flex; align-items: center; gap: 12px; background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; padding: 16px; margin-bottom: 1.5rem; cursor: pointer; }
        .confirm-check input[type="checkbox"] { width: 22px; height: 22px; accent-color: #16a085; }
        .confirm-check label { cursor: pointer; font-weight: 600; color: #166534; font-size: 14px; }

        /* موفقیت */
        .success-box { text-align: center; padding: 48px 24px; }
        .success-icon { font-size: 72px; margin-bottom: 16px; }
        .success-title { font-size: 24px; font-weight: 800; color: #166534; margin-bottom: 12px; }
        .success-desc { font-size: 15px; color: #64748b; line-height: 1.8; }

        .server-error { background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .server-error ul { list-style: none; padding: 0; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) {
            .form-container { padding: 1.5rem; }
            .form-row { grid-template-columns: 1fr; }
            .info-box { flex-direction: column; gap: 1rem; }
            .info-divider { width: 100%; height: 1px; }
            .payment-options { flex-direction: column; }
            .progress-label { font-size: 9px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title" id="formTitle">فرم ثبت‌نام</h1>
            <p class="form-subtitle">فیلد های "*" اجباری هستند</p>
            <p class="form-subtitle" id="stepLabel" style="margin-top:8px; font-weight:700; color:#16a085;">مرحله ۱ از ۴</p>
        </div>

        <!-- پروگرس بار -->
        <div class="progress-bar">
            <div class="progress-step">
                <div class="progress-circle active" id="pc1">۱</div>
                <div class="progress-label active" id="pl1">اطلاعات</div>
            </div>
            <div class="progress-line" id="pline1"></div>
            <div class="progress-step">
                <div class="progress-circle" id="pc2">۲</div>
                <div class="progress-label" id="pl2">پرداخت</div>
            </div>
            <div class="progress-line" id="pline2"></div>
            <div class="progress-step">
                <div class="progress-circle" id="pc3">۳</div>
                <div class="progress-label" id="pl3">تأیید</div>
            </div>
            <div class="progress-line" id="pline3"></div>
            <div class="progress-step">
                <div class="progress-circle" id="pc4">۴</div>
                <div class="progress-label" id="pl4">قرارداد</div>
            </div>
        </div>

        <div class="info-box">
            <div class="info-item">
                <div class="info-icon org">
                    <svg fill="none" stroke="#7c3aed" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div><div class="info-label">سازمان انتخابی</div><div class="info-value" id="orgDisplay">-</div></div>
            </div>
            <div class="info-divider"></div>
            <div class="info-item">
                <div class="info-icon province">
                    <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div><div class="info-label">استان انتخابی</div><div class="info-value" id="provDisplay">-</div></div>
            </div>
        </div>

        @if ($errors->any())
        <div class="server-error"><ul>@foreach ($errors->all() as $error)<li>⚠️ {{ $error }}</li>@endforeach</ul></div>
        @endif
        @if (session('error'))
        <div class="server-error">⚠️ {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('client.register.submit') }}" enctype="multipart/form-data" id="regForm">
            @csrf
            <input type="hidden" name="province" id="hProvince" value="{{ old('province') }}">
            <input type="hidden" name="organization" id="hOrganization" value="{{ old('organization') }}">
            <input type="hidden" name="payment_method" id="hPaymentMethod" value="">
            <input type="hidden" name="contract_accepted" id="hContractAccepted" value="0">

            <!-- ========== مرحله ۱: اطلاعات ========== -->
            <div class="step active" id="step1">
                <div class="form-section">
                    <h3 class="section-title">اطلاعات شخصی</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>نام و نام خانوادگی <span class="req">*</span></label>
                            <input type="text" name="full_name" id="fullName" placeholder="علی احمدی" required value="{{ old('full_name') }}">
                        </div>
                        <div class="form-group" style="position:relative;">
                            <label>شماره تلفن همراه <span class="req">*</span></label>
                            <input type="tel" name="phone" id="phoneInput" placeholder="09123456789" required value="{{ old('phone') }}"
                                maxlength="11" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,11);"
                                onkeypress="if(this.value.length>=11){showFieldToast('phoneInput','شماره تلفن باید ۱۱ رقم باشد','#f59e0b');return false;}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="position:relative;">
                            <label>کد ملی <span class="req">*</span></label>
                            <input type="text" name="national_id" id="nationalInput" placeholder="1234567890" required value="{{ old('national_id') }}"
                                maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10);"
                                onkeypress="if(this.value.length>=10){showFieldToast('nationalInput','کد ملی باید ۱۰ رقم باشد','#ef4444');return false;}">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">اطلاعات تراکتورها</h3>
                    <div id="tractorList">
                        <div class="tractor-card">
                            <div class="tractor-header"><span class="tractor-number">🚜 تراکتور ۱</span></div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>سیستم تراکتور <span class="req">*</span></label>
                                    <select name="tractors[0][system]" required><option value="">انتخاب کنید</option><option value="دو چرخ">دو چرخ</option><option value="چهار چرخ">چهار چرخ</option></select>
                                </div>
                                <div class="form-group">
                                    <label>تیپ <span class="req">*</span></label>
                                    <select name="tractors[0][type]" required><option value="">انتخاب کنید</option><option value="مسی فرگوسن">مسی فرگوسن</option><option value="جان دیر">جان دیر</option><option value="نیوهلند">نیوهلند</option><option value="یونیورسال">یونیورسال</option><option value="سایر">سایر</option></select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>تعداد سیلندر <span class="req">*</span></label>
                                    <select name="tractors[0][cylinders]" required><option value="">انتخاب کنید</option><option value="۲">۲ سیلندر</option><option value="۳">۳ سیلندر</option><option value="۴">۴ سیلندر</option><option value="۶">۶ سیلندر</option></select>
                                </div>
                                <div class="form-group">
                                    <label>برگ سبز</label>
                                    <div class="file-box" onclick="document.getElementById('gc0').click()"><p>📄 انتخاب فایل</p><small>حداکثر ۱ مگابایت - JPG, PNG, PDF</small></div>
                                    <input type="file" name="tractors[0][green_card]" id="gc0" accept="image/*,.pdf" onchange="fileChosen(this,0)">
                                    <div class="file-info" id="fi0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-add" onclick="addTractor()">➕ افزودن تراکتور جدید</button>
                </div>

                <div class="form-section">
                    <h3 class="section-title">محل سکونت</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>شهرستان <span class="req">*</span></label>
                            <select name="city" id="citySelect" required onchange="loadDistricts()"><option value="">انتخاب کنید</option><option value="آباده">آباده</option><option value="شیراز">شیراز</option><option value="مرودشت">مرودشت</option><option value="کازرون">کازرون</option><option value="لار">لار</option></select>
                        </div>
                        <div class="form-group">
                            <label>بخش <span class="req">*</span></label>
                            <select name="district" id="districtSelect" required onchange="loadVillages()"><option value="">ابتدا شهرستان انتخاب کنید</option></select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>روستا <span class="req">*</span></label>
                            <select name="village" id="villageSelect" required><option value="">ابتدا بخش انتخاب کنید</option></select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">آدرس محل نصب دستگاه</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>آدرس کامل <span class="req">*</span></label>
                            <input type="text" name="installation_address" id="installAddress" placeholder="آدرس کامل محل نصب..." required value="{{ old('installation_address') }}">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-back" onclick="history.back()">بازگشت</button>
                    <button type="button" class="btn-next" onclick="goToStep(2)">بعدی</button>
                </div>
            </div>

            <!-- ========== مرحله ۲: پرداخت ========== -->
            <div class="step" id="step2">
                <div class="form-section">
                    <div style="text-align:center; margin-bottom:20px;">
                        <p style="font-size:16px; color:#1e293b;"><span id="customerNameDisplay" style="font-weight:800; color:#16a085;"></span> گرامی، تعداد تراکتور شما <span id="tractorCountDisplay" style="font-weight:800; color:#16a085;"></span> می باشد</p>
                        <p style="font-size:14px; color:#64748b; margin-top:4px;">قیمت نهایی هر دستگاه <span style="font-weight:700; color:#1e293b;">۱۱,۰۰۰,۰۰۰ تومان</span> با احتساب مالیات می باشد</p>
                    </div>

                    <div class="invoice-box">
                        <div class="invoice-header">🧾 سفارش</div>
                        <div class="invoice-row"><span class="label">محصول</span><span class="label">تعداد</span><span class="label">قیمت واحد</span><span class="value">قیمت</span></div>
                        <div class="invoice-row"><span class="label">دستگاه ویرامپ</span><span class="label" id="invCount">1</span><span class="label">10,000,000 تومان</span><span class="value" id="invDevice">10,000,000 تومان</span></div>
                        <div class="invoice-row"><span class="label">سیم کارت + شارژ اولیه</span><span class="label" id="invCount2">1</span><span class="label">0 تومان</span><span class="value">0 تومان</span></div>
                        <div class="invoice-row"><span class="label">مالیات</span><span class="label" id="invCount3">1</span><span class="label">1,000,000 تومان</span><span class="value" id="invTax">1,000,000 تومان</span></div>
                        <div class="invoice-total"><span class="label">مجموع</span><span class="value" id="invTotal">11,000,000 تومان</span></div>
                    </div>

                    <h3 class="section-title">روش پرداخت</h3>
                    <div class="payment-options">
                        <div class="payment-option" id="payOnline" onclick="selectPayment('online')">
                            <div class="icon">🏦</div>
                            <div class="title">پرداخت آنلاین</div>
                            <div class="desc">درگاه بانکی</div>
                        </div>
                        <div class="payment-option" id="payTransfer" onclick="selectPayment('transfer')">
                            <div class="icon">💳</div>
                            <div class="title">واریز وجه – کارت به کارت</div>
                            <div class="desc">آپلود فیش بانکی</div>
                        </div>
                    </div>

                    <!-- آپلود فیش -->
                    <div id="transferSection" style="display:none;">
                        <div class="form-section">
                            <h3 class="section-title">بارگذاری فیش بانکی</h3>
                            <div class="form-group">
                                <div class="file-box" onclick="document.getElementById('receiptFile').click()">
                                    <p>🧾 انتخاب تصویر فیش بانکی</p>
                                    <small>حداکثر ۵ مگابایت - JPG, PNG, PDF</small>
                                </div>
                                <input type="file" name="payment_receipt" id="receiptFile" accept="image/*,.pdf" onchange="receiptChosen(this)">
                                <div class="file-info" id="receiptInfo"></div>
                            </div>
                        </div>
                    </div>

                    <!-- درگاه آنلاین -->
                    <div id="onlineSection" style="display:none;">
                        <div style="background:#dbeafe; border:2px solid #93c5fd; border-radius:12px; padding:20px; text-align:center; color:#1e40af; font-weight:600;">
                            🏦 پس از تکمیل فرم و تأیید قرارداد، به درگاه پرداخت هدایت خواهید شد
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-prev" onclick="goToStep(1)">قبلی</button>
                    <button type="button" class="btn-next" onclick="goToStep(3)">بعدی</button>
                </div>
            </div>

            <!-- ========== مرحله ۳: تأیید اطلاعات ========== -->
            <div class="step" id="step3">
                <div class="form-section">
                    <h3 class="section-title">بررسی اطلاعات</h3>

                    <div class="summary-card">
                        <div class="card-title">👤 اطلاعات شخصی</div>
                        <div class="summary-row"><span class="s-label">نام و نام خانوادگی</span><span class="s-value" id="sumName">-</span></div>
                        <div class="summary-row"><span class="s-label">شماره تلفن</span><span class="s-value" id="sumPhone">-</span></div>
                        <div class="summary-row"><span class="s-label">کد ملی</span><span class="s-value" id="sumNid">-</span></div>
                    </div>

                    <div class="summary-card">
                        <div class="card-title">📍 محل سکونت</div>
                        <div class="summary-row"><span class="s-label">استان</span><span class="s-value" id="sumProv">-</span></div>
                        <div class="summary-row"><span class="s-label">شهرستان</span><span class="s-value" id="sumCity">-</span></div>
                        <div class="summary-row"><span class="s-label">بخش</span><span class="s-value" id="sumDist">-</span></div>
                        <div class="summary-row"><span class="s-label">روستا</span><span class="s-value" id="sumVill">-</span></div>
                        <div class="summary-row"><span class="s-label">آدرس نصب</span><span class="s-value" id="sumAddr">-</span></div>
                    </div>

                    <div class="summary-card">
                        <div class="card-title">💰 پرداخت</div>
                        <div class="summary-row"><span class="s-label">نحوه پرداخت</span><span class="s-value" id="sumPayment">-</span></div>
                        <div class="summary-row" id="sumReceiptRow" style="display:none;"><span class="s-label">فیش بانکی</span><span class="s-value" id="sumReceipt">-</span></div>
                        <div class="summary-row"><span class="s-label">وضعیت حساب</span><span class="s-value" id="sumStatus">در انتظار بررسی</span></div>
                    </div>

                    <div class="invoice-box">
                        <div class="invoice-header">🧾 فاکتور نهایی</div>
                        <div class="invoice-row"><span class="label">دستگاه ویرامپ</span><span class="value" id="sumDevPrice">10,000,000 تومان</span></div>
                        <div class="invoice-row"><span class="label">سیم کارت + شارژ اولیه</span><span class="value">0 تومان</span></div>
                        <div class="invoice-row"><span class="label">مالیات</span><span class="value" id="sumTaxPrice">1,000,000 تومان</span></div>
                        <div class="invoice-total"><span class="label">مجموع</span><span class="value" id="sumTotalPrice">11,000,000 تومان</span></div>
                    </div>

                    <div class="confirm-check">
                        <input type="checkbox" id="confirmInfo">
                        <label for="confirmInfo">کلیه اطلاعات مورد تایید اینجانب می باشد</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-prev" onclick="goToStep(2)">قبلی</button>
                    <button type="button" class="btn-next" onclick="goToStep(4)" id="btnToContract">بعدی</button>
                </div>
            </div>

            <!-- ========== مرحله ۴: قرارداد ========== -->
            <div class="step" id="step4">
                <div class="form-section">
                    <h3 class="section-title">قرارداد</h3>

                    <div class="contract-box">
                        <h4>بسمه تعالی</h4>
                        <p>این قرارداد فی مابین شرکت مدیران ویرا گستر نامی به شماره ثبت 59491 و به آدرس: شیراز – پارک علم و فناوری فارس به عنوان شرکت و آقای / خانم <strong id="contractName">—</strong> به کدملی <strong id="contractNid">—</strong> و شماره موبایل <strong id="contractPhone">—</strong> و آدرس <strong id="contractAddr">—</strong> که تراکتوردار نامیده می شود و سازمان <strong id="contractOrg">—</strong> که به عنوان ناظر به شرح ذیل منعقد می‌گردد.</p>
                        <br>
                        <p><strong>موضوع قرارداد:</strong> نصب دستگاه پایشگر ویرامپ به منظور پایش سوخت تراکتور و ارائه گزارش به سازمان جهاد کشاورزی استان فارس</p>
                        <br>
                        <p><strong>مدت قرارداد:</strong> از تاریخ امروز به مدت یک سال شمسی می باشد که درصورت عدم فسخ قرارداد بصورت کتبی این قرارداد بصورت اتوماتیک برای یک سال دیگر تمدید می گردد.</p>
                        <br>
                        <p><strong>مبلغ قرارداد:</strong> دستگاه به مبلغ 100,000,000 ریال بدون محاسبه مالیات و ارزش افزوده می باشد که کل مبلغ باید به صورت آنلاین از درگاه پرداخت ویرامپ به نام شرکت مدیران ویرا گستر نامی واریز گردد.</p>
                        <br>
                        <p><strong>تعهدات:</strong></p>
                        <p>۱- هرگونه تغییرات در مسیر سوخت رسانی و برق کشی دستگاه بدون هماهنگی با ناظر فنی شرکت غیرقانونی می باشد و شرکت می تواند دستگاه را غیرفعال نماید.</p>
                        <p>۲- بازکردن دستگاه و دستکاری در دستگاه غیر قانونی می باشد و شرکت می تواند طرح شکایت و دستگاه را غیرفعال نماید.</p>
                        <p>۳- سوخت مصرفی تماما بر اساس روش های مختلف پایش سوخت محاسبه می گردد.</p>
                        <p>۴- دستگاه جهت پایش سوخت تراکتورهای استان فارس می باشد و در صورت خروج از استان امکان تامین سوخت وجود ندارد.</p>
                        <p>۵- هرگونه خرید و فروش تراکتور بدون هماهنگی با شرکت سبب غیرفعال شدن دستگاه می شود.</p>
                        <p>۶- سیم کارت دیتا به نام شرکت بوده و حق بهره برداری از آن برعهده تراکتوردار می باشد.</p>
                        <p>۷- در صورت ارسال فاکتور پشتیبانی سالیانه، تراکتوردار موظف است ظرف ۲۴ ساعت پرداخت نماید.</p>
                        <p>۸- اپلیکیشنی جهت نمایش اطلاعات در اختیار شما قرار می گیرد.</p>
                        <p>۹- دستگاه دارای یک سال گارانتی رایگان و ده سال خدمات پس از فروش می باشد.</p>
                        <p>۱۰- پشتیبانی فنی نرم افزاری و تامین قطعات بر عهده شرکت می باشد.</p>
                        <p>۱۱- تراکتوردار موظف است جهت بازدیدهای دوره ای همکاری لازم را انجام دهد.</p>
                        <p>۱۲- شرکت و تراکتوردار متعهد به حفظ محرمانگی اطلاعات می شوند.</p>
                        <p>۱۳- در صورت بروز اختلاف، سازمان جهاد کشاورزی به عنوان داور می باشد.</p>
                        <p>۱۴- این قرارداد بصورت الکترونیک به امضا طرفین می رسد و قابلیت پیگیری قانونی دارد.</p>
                    </div>

                    <div class="confirm-check">
                        <input type="checkbox" id="confirmContract" onchange="document.getElementById('hContractAccepted').value = this.checked ? '1' : '0';">
                        <label for="confirmContract">تمامی بندهای قرارداد فوق را مطالعه نموده و مورد تأیید اینجانب می باشد</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-prev" onclick="goToStep(3)">قبلی</button>
                    <button type="submit" class="btn-submit" id="btnFinalSubmit">ثبت نهایی و تأیید قرارداد</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var tc = 1;
var currentStep = 1;
var selectedPayment = '';

function goToStep(step) {
    // اعتبارسنجی مرحله ۱
    if (step === 2 && currentStep === 1) {
        var fn = document.getElementById('fullName').value.trim();
        var ph = document.getElementById('phoneInput').value.trim();
        var ni = document.getElementById('nationalInput').value.trim();
        var ci = document.getElementById('citySelect').value;
        var di = document.getElementById('districtSelect').value;
        var vi = document.getElementById('villageSelect').value;
        var ad = document.getElementById('installAddress').value.trim();
        if (!fn || !ph || !ni || !ci || !di || !vi || !ad) {
            showFieldToast('fullName', 'لطفاً تمام فیلدهای اجباری را پر کنید', '#ef4444');
            return;
        }
        if (ph.length !== 11) { showFieldToast('phoneInput', 'شماره تلفن باید ۱۱ رقم باشد', '#f59e0b'); return; }
        if (ni.length !== 10) { showFieldToast('nationalInput', 'کد ملی باید ۱۰ رقم باشد', '#ef4444'); return; }
        // آپدیت مرحله ۲
        document.getElementById('customerNameDisplay').textContent = fn;
        var tractorCount = document.querySelectorAll('.tractor-card').length;
        document.getElementById('tractorCountDisplay').textContent = tractorCount + ' تراکتور';
        updateInvoice(tractorCount);
    }

    // اعتبارسنجی مرحله ۲
    if (step === 3 && currentStep === 2) {
        if (!selectedPayment) {
            showFieldToast('payOnline', 'لطفاً روش پرداخت را انتخاب کنید', '#ef4444');
            return;
        }
        if (selectedPayment === 'transfer' && !document.getElementById('receiptFile').files.length) {
            showFieldToast('receiptFile', 'لطفاً فیش بانکی را آپلود کنید', '#ef4444');
            return;
        }
        populateSummary();
    }

    // اعتبارسنجی مرحله ۳
    if (step === 4 && currentStep === 3) {
        if (!document.getElementById('confirmInfo').checked) {
            showFieldToast('confirmInfo', 'لطفاً اطلاعات را تأیید کنید', '#ef4444');
            return;
        }
        populateContract();
    }

    // تغییر مرحله
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
    currentStep = step;
    updateProgress(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateProgress(step) {
    for (var i = 1; i <= 4; i++) {
        var c = document.getElementById('pc' + i);
        var l = document.getElementById('pl' + i);
        c.classList.remove('active', 'done');
        l.classList.remove('active', 'done');
        if (i < step) { c.classList.add('done'); l.classList.add('done'); c.textContent = '✓'; }
        else if (i === step) { c.classList.add('active'); l.classList.add('active'); }
        else { c.textContent = ['', '۱', '۲', '۳', '۴'][i]; }
    }
    for (var i = 1; i <= 3; i++) {
        var ln = document.getElementById('pline' + i);
        ln.classList.toggle('active', i < step);
    }
    document.getElementById('stepLabel').textContent = 'مرحله ' + step + ' از ۴';
}

function selectPayment(method) {
    selectedPayment = method;
    document.getElementById('hPaymentMethod').value = method;
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    document.getElementById(method === 'online' ? 'payOnline' : 'payTransfer').classList.add('selected');
    document.getElementById('transferSection').style.display = method === 'transfer' ? 'block' : 'none';
    document.getElementById('onlineSection').style.display = method === 'online' ? 'block' : 'none';
}

function updateInvoice(count) {
    var devPrice = count * 10000000;
    var taxPrice = count * 1000000;
    var total = devPrice + taxPrice;
    document.getElementById('invCount').textContent = count;
    document.getElementById('invCount2').textContent = count;
    document.getElementById('invCount3').textContent = count;
    document.getElementById('invDevice').textContent = devPrice.toLocaleString() + ' تومان';
    document.getElementById('invTax').textContent = taxPrice.toLocaleString() + ' تومان';
    document.getElementById('invTotal').textContent = total.toLocaleString() + ' تومان';
}

function populateSummary() {
    document.getElementById('sumName').textContent = document.getElementById('fullName').value;
    document.getElementById('sumPhone').textContent = document.getElementById('phoneInput').value;
    document.getElementById('sumNid').textContent = document.getElementById('nationalInput').value;
    document.getElementById('sumProv').textContent = document.getElementById('provDisplay').textContent;
    document.getElementById('sumCity').textContent = document.getElementById('citySelect').value;
    document.getElementById('sumDist').textContent = document.getElementById('districtSelect').value;
    document.getElementById('sumVill').textContent = document.getElementById('villageSelect').value;
    document.getElementById('sumAddr').textContent = document.getElementById('installAddress').value;
    document.getElementById('sumPayment').textContent = selectedPayment === 'online' ? 'پرداخت آنلاین' : 'واریز وجه – کارت به کارت';
    if (selectedPayment === 'transfer' && document.getElementById('receiptFile').files.length) {
        document.getElementById('sumReceiptRow').style.display = 'flex';
        document.getElementById('sumReceipt').textContent = document.getElementById('receiptFile').files[0].name;
    }
    var count = document.querySelectorAll('.tractor-card').length;
    document.getElementById('sumDevPrice').textContent = (count * 10000000).toLocaleString() + ' تومان';
    document.getElementById('sumTaxPrice').textContent = (count * 1000000).toLocaleString() + ' تومان';
    document.getElementById('sumTotalPrice').textContent = (count * 11000000).toLocaleString() + ' تومان';
}

function populateContract() {
    document.getElementById('contractName').textContent = document.getElementById('fullName').value;
    document.getElementById('contractNid').textContent = document.getElementById('nationalInput').value;
    document.getElementById('contractPhone').textContent = document.getElementById('phoneInput').value;
    var addr = document.getElementById('citySelect').value + ' - ' + document.getElementById('villageSelect').value;
    document.getElementById('contractAddr').textContent = addr;
    document.getElementById('contractOrg').textContent = document.getElementById('orgDisplay').textContent;
}

function receiptChosen(input) {
    var info = document.getElementById('receiptInfo');
    if (input.files.length > 0) { info.textContent = '📎 ' + input.files[0].name; }
}

// تأیید نهایی
document.getElementById('regForm').addEventListener('submit', function(e) {
    if (!document.getElementById('confirmContract').checked) {
        e.preventDefault();
        showFieldToast('confirmContract', 'لطفاً قرارداد را تأیید کنید', '#ef4444');
    }
});

// توابع قبلی
function fileChosen(input, idx) {
    var info = document.getElementById('fi' + idx);
    if (input.files.length > 0) { info.textContent = '📎 ' + input.files[0].name; }
}

function addTractor() {
    var list = document.getElementById('tractorList');
    var d = document.createElement('div');
    d.className = 'tractor-card';
    d.innerHTML = '<div class="tractor-header"><span class="tractor-number">🚜 تراکتور '+(tc+1)+'</span><button type="button" class="btn-remove" onclick="this.closest(\'.tractor-card\').remove()">حذف</button></div>'
        + '<div class="form-row"><div class="form-group"><label>سیستم تراکتور <span class="req">*</span></label><select name="tractors['+tc+'][system]" required><option value="">انتخاب کنید</option><option value="دو چرخ">دو چرخ</option><option value="چهار چرخ">چهار چرخ</option></select></div>'
        + '<div class="form-group"><label>تیپ <span class="req">*</span></label><select name="tractors['+tc+'][type]" required><option value="">انتخاب کنید</option><option value="مسی فرگوسن">مسی فرگوسن</option><option value="جان دیر">جان دیر</option><option value="نیوهلند">نیوهلند</option><option value="یونیورسال">یونیورسال</option><option value="سایر">سایر</option></select></div></div>'
        + '<div class="form-row"><div class="form-group"><label>تعداد سیلندر <span class="req">*</span></label><select name="tractors['+tc+'][cylinders]" required><option value="">انتخاب کنید</option><option value="۲">۲ سیلندر</option><option value="۳">۳ سیلندر</option><option value="۴">۴ سیلندر</option><option value="۶">۶ سیلندر</option></select></div>'
        + '<div class="form-group"><label>برگ سبز</label><div class="file-box" onclick="document.getElementById(\'gc'+tc+'\').click()"><p>📄 انتخاب فایل</p><small>حداکثر ۱ مگابایت</small></div><input type="file" name="tractors['+tc+'][green_card]" id="gc'+tc+'" accept="image/*,.pdf" onchange="fileChosen(this,'+tc+')"><div class="file-info" id="fi'+tc+'"></div></div></div>';
    list.appendChild(d);
    tc++;
}

var districtData = { 'شیراز':['مرکزی','ارژن','سروستان'], 'مرودشت':['مرکزی','کامفیروز','سعادت شهر'], 'آباده':['مرکزی','سورمق','بهمن'] };
var villageData = { 'مرکزی':['روستای ۱','روستای ۲','روستای ۳'], 'ارژن':['روستای ۴','روستای ۵'] };

function loadDistricts() {
    var c = document.getElementById('citySelect').value;
    var ds = document.getElementById('districtSelect');
    var vs = document.getElementById('villageSelect');
    ds.innerHTML = '<option value="">انتخاب کنید</option>';
    vs.innerHTML = '<option value="">ابتدا بخش انتخاب کنید</option>';
    if (districtData[c]) { for (var i=0;i<districtData[c].length;i++) { ds.innerHTML += '<option value="'+districtData[c][i]+'">'+districtData[c][i]+'</option>'; } }
}

function loadVillages() {
    var d = document.getElementById('districtSelect').value;
    var vs = document.getElementById('villageSelect');
    vs.innerHTML = '<option value="">انتخاب کنید</option>';
    if (villageData[d]) { for (var i=0;i<villageData[d].length;i++) { vs.innerHTML += '<option value="'+villageData[d][i]+'">'+villageData[d][i]+'</option>'; } }
}

var params = new URLSearchParams(window.location.search);
var prov = params.get('province') || '';
var org = params.get('organization') || '';
if (prov) { document.getElementById('provDisplay').textContent = prov; document.getElementById('hProvince').value = prov; }
if (org) { document.getElementById('orgDisplay').textContent = org; document.getElementById('hOrganization').value = org; document.getElementById('formTitle').textContent = 'فرم ثبت‌نام ' + org; }

function showFieldToast(inputId, message, color) {
    const old = document.getElementById('fieldToast');
    if (old) old.remove();
    const input = document.getElementById(inputId);
    const rect = input.getBoundingClientRect();
    const toast = document.createElement('div');
    toast.id = 'fieldToast';
    toast.innerHTML = '<div style="position:fixed;top:'+(rect.top-80)+'px;left:'+rect.left+'px;width:'+rect.width+'px;z-index:99999;background:white;border-radius:16px;box-shadow:0 12px 50px rgba(0,0,0,0.18);padding:18px 22px;direction:rtl;display:flex;align-items:center;gap:14px;border-right:5px solid '+color+';animation:toastPop 0.35s cubic-bezier(0.16,1,0.3,1);font-family:Vazirmatn,sans-serif;"><div style="width:48px;height:48px;border-radius:14px;background:'+color+'20;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">⚠️</div><div style="flex:1;"><div style="font-weight:800;font-size:15px;color:#1e293b;margin-bottom:4px;">محدودیت ورودی</div><div style="font-size:14px;color:#475569;line-height:1.6;">'+message+'</div></div><button onclick="this.closest(\'#fieldToast\').remove()" style="background:'+color+'15;border:none;color:'+color+';cursor:pointer;font-size:18px;padding:8px;border-radius:10px;line-height:1;font-weight:700;">✕</button></div>';
    document.body.appendChild(toast);
    input.style.animation = 'inputShake 0.4s ease';
    input.style.boxShadow = '0 0 0 3px ' + color + '40';
    setTimeout(() => { input.style.animation = ''; input.style.boxShadow = ''; }, 600);
    setTimeout(() => { const el = document.getElementById('fieldToast'); if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); } }, 3000);
}
</script>
<style>
@keyframes toastPop { from{opacity:0;transform:scale(0.9) translateY(10px);} to{opacity:1;transform:scale(1) translateY(0);} }
@keyframes inputShake { 0%,100%{transform:translateX(0);} 20%{transform:translateX(-6px);} 40%{transform:translateX(6px);} 60%{transform:translateX(-4px);} 80%{transform:translateX(4px);} }
</style>
</body>
</html>