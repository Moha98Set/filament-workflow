<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فرم ثبت‌نام - جهاد کشاورزی</title>
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Alert Modal */
        .alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
            backdrop-filter: blur(5px);
        }

        .alert-overlay.hidden {
            display: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .alert-box {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .alert-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .alert-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            text-align: center;
            margin-bottom: 1rem;
        }

        .alert-message {
            font-size: 1.1rem;
            color: #4a5568;
            text-align: center;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .alert-province {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .alert-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-dismiss {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            background: white;
            color: #4a5568;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Vazirmatn', sans-serif;
        }

        .btn-dismiss:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .btn-continue {
            flex: 1;
            padding: 1rem;
            border: none;
            background: linear-gradient(135deg, #16a085 0%, #0f9b6b 100%);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Vazirmatn', sans-serif;
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        }

        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 160, 133, 0.4);
        }

        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            display: none;
        }

        .form-container.active {
            display: block;
            animation: fadeInUp 0.6s ease-out;
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

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #718096;
            font-size: 1rem;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #16a085 0%, #0f9b6b 100%);
            width: 33%;
            transition: width 0.3s ease;
            border-radius: 10px;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #16a085;
            display: inline-block;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        label .required {
            color: #e53e3e;
        }

        input, select {
            padding: 0.875rem 1.125rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Vazirmatn', sans-serif;
            transition: all 0.3s;
            background: white;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #16a085;
            box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1);
        }

        input::placeholder {
            color: #a0aec0;
        }

        /* Tractor Section */
        .tractor-card {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            transition: all 0.3s;
        }

        .tractor-card:hover {
            border-color: #16a085;
            box-shadow: 0 4px 12px rgba(22, 160, 133, 0.1);
        }

        .tractor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .tractor-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #16a085;
        }

        .btn-remove-tractor {
            padding: 0.5rem 1rem;
            background: #feb2b2;
            border: none;
            border-radius: 8px;
            color: #742a2a;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Vazirmatn', sans-serif;
        }

        .btn-remove-tractor:hover {
            background: #fc8181;
        }

        .btn-add-tractor {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Vazirmatn', sans-serif;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-tractor:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f7fafc;
        }

        .file-upload:hover {
            border-color: #16a085;
            background: #f0fdf4;
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #16a085;
            margin-bottom: 1rem;
        }

        .file-upload-text {
            color: #4a5568;
            font-weight: 600;
        }

        .file-upload-hint {
            color: #a0aec0;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        input[type="file"] {
            display: none;
        }

        /* Submit Button */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-back {
            flex: 1;
            padding: 1.25rem;
            background: white;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Vazirmatn', sans-serif;
        }

        .btn-back:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .btn-submit {
            flex: 2;
            padding: 1.25rem;
            background: linear-gradient(135deg, #16a085 0%, #0f9b6b 100%);
            border: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Vazirmatn', sans-serif;
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(22, 160, 133, 0.4);
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .alert-box {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
<!-- Alert Overlay -->
<div class="alert-overlay" id="alertOverlay">
    <div class="alert-box">
        <div class="alert-icon">⚠️</div>
        <h2 class="alert-title">تأیید ثبت در جهاد کشاورزی</h2>
        <p class="alert-message">
            شما در حال ثبت اطلاعات در <strong>جهاد کشاورزی استان <span id="provinceName">فارس</span></strong> هستید.
            لطفاً فرم را با دقت و صحت کامل تکمیل کنید. با انتخاب دکمه زیر، مسئولیت صحت اطلاعات را می‌پذیرید.
        </p>
        <div class="alert-province">
            استان: <span id="provinceNameBottom">فارس</span>
        </div>
        <div class="alert-buttons">
            <button class="btn-dismiss" onclick="goBack()">انصراف</button>
            <button class="btn-continue" onclick="closeAlert()">شروع تکمیل فرم</button>
        </div>
    </div>
</div>

<!-- Registration Form -->
<div class="container">
    <div class="form-container" id="formContainer">
        <div class="form-header">
            <h1 class="form-title">فرم ثبت‌نام جهاد کشاورزی</h1>
            <p class="form-subtitle">لطفاً تمام فیلدهای ستاره‌دار (*) را تکمیل نمایید</p>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        <form method="POST" action="{{ route('client.register.submit') }}" enctype="multipart/form-data">
            @csrf

            {{-- نمایش سازمان و استان انتخاب شده --}}
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 border-r-4 border-purple-500 p-6 rounded-lg mb-8 shadow-md">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">سازمان انتخابی</p>
                            <p class="text-xl font-bold text-purple-700" id="selected-organization">در حال بارگذاری...</p>
                        </div>
                    </div>
                    
                    <div class="h-12 w-px bg-gray-300 hidden md:block"></div>
                    
                    <div class="flex items-center gap-3 flex-1">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">استان انتخابی</p>
                            <p class="text-xl font-bold text-blue-700" id="selected-province">در حال بارگذاری...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اطلاعات شخصی -->
            <div class="form-section">
                <h3 class="section-title">اطلاعات شخصی</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>نام و نام خانوادگی <span class="required">*</span></label>
                        <input type="text" name="full_name" placeholder="علی احمدی" required>
                    </div>
                    <div class="form-group">
                        <label>شماره تلفن همراه <span class="required">*</span></label>
                        <input type="tel" name="phone" placeholder="09123456789" pattern="09[0-9]{9}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>کد ملی <span class="required">*</span></label>
                        <input type="text" name="national_id" placeholder="1234567890" pattern="[0-9]{10}" required>
                    </div>
                </div>
            </div>

            <!-- اطلاعات تراکتورها -->
            <div class="form-section">
                <h3 class="section-title">اطلاعات تراکتورها</h3>
                <div id="tractorsContainer">
                    <!-- Tractor 1 -->
                    <div class="tractor-card">
                        <div class="tractor-header">
                            <span class="tractor-number">🚜 تراکتور ۱</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>سیستم تراکتور <span class="required">*</span></label>
                                <select name="tractors[0][system]" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="دو چرخ">دو چرخ</option>
                                    <option value="چهار چرخ">چهار چرخ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>تیپ <span class="required">*</span></label>
                                <select name="tractors[0][type]" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="مسی فرگوسن">مسی فرگوسن</option>
                                    <option value="جان دیر">جان دیر</option>
                                    <option value="نیوهلند">نیوهلند</option>
                                    <option value="یونیورسال">یونیورسال</option>
                                    <option value="سایر">سایر</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>تعداد سیلندر <span class="required">*</span></label>
                                <select name="tractors[0][cylinders]" required>
                                    <option value="">انتخاب کنید</option>
                                    <option value="۲">۲ سیلندر</option>
                                    <option value="۳">۳ سیلندر</option>
                                    <option value="۴">۴ سیلندر</option>
                                    <option value="۶">۶ سیلندر</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>برگ سبز <span class="required">*</span></label>
                                <div class="file-upload" onclick="document.getElementById('greenCard0').click()">
                                    <div class="file-upload-icon">📄</div>
                                    <div class="file-upload-text">فایل را انتخاب یا اینجا بکشید</div>
                                    <div class="file-upload-hint">حداکثر ۱ مگابایت - JPG, PNG, PDF</div>
                                </div>
                                <input type="file" name="tractors[0][green_card]" id="greenCard0" accept="image/*,.pdf" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-add-tractor" onclick="addTractor()">
                    <span>➕</span>
                    <span>افزودن تراکتور جدید</span>
                </button>
            </div>

            <!-- اطلاعات محل سکونت -->
            <div class="form-section">
                <h3 class="section-title">محل سکونت</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>شهرستان <span class="required">*</span></label>
                        <select name="city" id="citySelect" required onchange="updateDistricts()">
                            <option value="">انتخاب کنید</option>
                            <option value="آباده">آباده</option>
                            <option value="شیراز">شیراز</option>
                            <option value="مرودشت">مرودشت</option>
                            <option value="کازرون">کازرون</option>
                            <option value="لار">لار</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>بخش <span class="required">*</span></label>
                        <select name="district" id="districtSelect" required onchange="updateVillages()">
                            <option value="">ابتدا شهرستان را انتخاب کنید</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>روستا <span class="required">*</span></label>
                        <select name="village" id="villageSelect" required>
                            <option value="">ابتدا بخش را انتخاب کنید</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- آدرس محل نصب دستگاه -->
            <div class="form-section">
                <h3 class="section-title">آدرس محل نصب دستگاه</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>آدرس کامل <span class="required">*</span></label>
                        <input type="text" name="installation_address" placeholder="استان، شهرستان، روستا، خیابان، پلاک..." required>
                    </div>
                </div>
            </div>

            <!-- دکمه‌های فرم -->
            <div class="form-actions">
                <button type="button" class="btn-back" onclick="goBack()">بازگشت</button>
                <button type="submit" class="btn-submit">ثبت اطلاعات</button>
            </div>
        </form>
    </div>
</div>

<script>
    let tractorCount = 1;

    // بستن Alert و نمایش فرم
    function closeAlert() {
        document.getElementById('alertOverlay').classList.add('hidden');
        document.getElementById('formContainer').classList.add('active');
        updateProgress();
    }

    // بازگشت به صفحه قبل
    function goBack() {
        window.history.back();
    }

    // افزودن تراکتور جدید
    function addTractor() {
        const container = document.getElementById('tractorsContainer');
        const newTractor = `
                <div class="tractor-card">
                    <div class="tractor-header">
                        <span class="tractor-number">🚜 تراکتور ${tractorCount + 1}</span>
                        <button type="button" class="btn-remove-tractor" onclick="removeTractor(this)">حذف</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>سیستم تراکتور <span class="required">*</span></label>
                            <select name="tractors[${tractorCount}][system]" required>
                                <option value="">انتخاب کنید</option>
                                <option value="دو چرخ">دو چرخ</option>
                                <option value="چهار چرخ">چهار چرخ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>تیپ <span class="required">*</span></label>
                            <select name="tractors[${tractorCount}][type]" required>
                                <option value="">انتخاب کنید</option>
                                <option value="مسی فرگوسن">مسی فرگوسن</option>
                                <option value="جان دیر">جان دیر</option>
                                <option value="نیوهلند">نیوهلند</option>
                                <option value="یونیورسال">یونیورسال</option>
                                <option value="سایر">سایر</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>تعداد سیلندر <span class="required">*</span></label>
                            <select name="tractors[${tractorCount}][cylinders]" required>
                                <option value="">انتخاب کنید</option>
                                <option value="۲">۲ سیلندر</option>
                                <option value="۳">۳ سیلندر</option>
                                <option value="۴">۴ سیلندر</option>
                                <option value="۶">۶ سیلندر</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>برگ سبز <span class="required">*</span></label>
                            <div class="file-upload" onclick="document.getElementById('greenCard${tractorCount}').click()">
                                <div class="file-upload-icon">📄</div>
                                <div class="file-upload-text">فایل را انتخاب یا اینجا بکشید</div>
                                <div class="file-upload-hint">حداکثر ۱ مگابایت - JPG, PNG, PDF</div>
                            </div>
                            <input type="file" name="tractors[${tractorCount}][green_card]" id="greenCard${tractorCount}" accept="image/*,.pdf" required>
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', newTractor);
        tractorCount++;
        updateProgress();
    }

    // حذف تراکتور
    function removeTractor(btn) {
        btn.closest('.tractor-card').remove();
        updateProgress();
    }

    // به‌روزرسانی نوار پیشرفت
    function updateProgress() {
        const form = document.getElementById('registrationForm');
        const inputs = form.querySelectorAll('input[required], select[required]');
        const filled = Array.from(inputs).filter(input => input.value !== '').length;
        const progress = (filled / inputs.length) * 100;
        document.getElementById('progressFill').style.width = progress + '%';
    }

    // به‌روزرسانی بخش‌ها بر اساس شهرستان
    function updateDistricts() {
        const city = document.getElementById('citySelect').value;
        const districtSelect = document.getElementById('districtSelect');
        const villageSelect = document.getElementById('villageSelect');

        // پاک کردن گزینه‌های قبلی
        districtSelect.innerHTML = '<option value="">انتخاب کنید</option>';
        villageSelect.innerHTML = '<option value="">ابتدا بخش را انتخاب کنید</option>';

        // نمونه داده‌ها (باید از API یا دیتابیس بیاید)
        const districts = {
            'شیراز': ['مرکزی', 'ارژن', 'سروستان'],
            'مرودشت': ['مرکزی', 'کامفیروز', 'سعادت شهر'],
            'آباده': ['مرکزی', 'سورمق', 'بهمن'],
        };

        if (districts[city]) {
            districts[city].forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    }

    // به‌روزرسانی روستاها بر اساس بخش
    function updateVillages() {
        const district = document.getElementById('districtSelect').value;
        const villageSelect = document.getElementById('villageSelect');

        villageSelect.innerHTML = '<option value="">انتخاب کنید</option>';

        // نمونه داده‌ها
        const villages = {
            'مرکزی': ['روستای ۱', 'روستای ۲', 'روستای ۳'],
            'ارژن': ['روستای ۴', 'روستای ۵'],
        };

        if (villages[district]) {
            villages[district].forEach(village => {
                const option = document.createElement('option');
                option.value = village;
                option.textContent = village;
                villageSelect.appendChild(option);
            });
        }
    }

    // به‌روزرسانی استان در Alert
    window.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const province = urlParams.get('province') || 'فارس';
        const organization = urlParams.get('org') || 'jihad';

        document.getElementById('provinceName').textContent = province;
        document.getElementById('provinceNameBottom').textContent = province;

        // مقداردهی به فیلدهای مخفی
        document.getElementById('provinceInput').value = province;
        document.getElementById('organizationInput').value = organization;

        // رصد تغییرات فرم برای نوار پیشرفت
        const form = document.getElementById('registrationForm');
        form.addEventListener('change', updateProgress);
        form.addEventListener('input', updateProgress);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // دریافت از URL Parameters
    const urlParams = new URLSearchParams(window.location.search);
    let province = urlParams.get('province');
    let organization = urlParams.get('organization');
    
    // اگر در URL نبود، از localStorage بخون
    if (!province) {
        province = localStorage.getItem('selected_province');
    }
    if (!organization) {
        organization = localStorage.getItem('selected_organization');
    }
    
    // نمایش در صفحه
    if (province) {
        document.getElementById('selected-province').textContent = province;
        document.getElementById('province-input').value = province;
    } else {
        document.getElementById('selected-province').textContent = 'انتخاب نشده';
    }
    
    if (organization) {
        document.getElementById('selected-organization').textContent = organization;
        document.getElementById('organization-input').value = organization;
    } else {
        document.getElementById('selected-organization').textContent = 'انتخاب نشده';
    }
});
</script>
</body>
</html>
