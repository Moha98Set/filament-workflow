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
        .form-header { text-align: center; margin-bottom: 2.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid #e2e8f0; }
        .form-title { font-size: 2rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem; }
        .form-subtitle { color: #718096; font-size: 1rem; }
        .info-box { background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%); border-right: 4px solid #667eea; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem; }
        .info-item { display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 200px; }
        .info-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .info-icon.org { background: #ede9fe; }
        .info-icon.province { background: #dbeafe; }
        .info-icon svg { width: 24px; height: 24px; }
        .info-label { font-size: 0.85rem; color: #6b7280; font-weight: 500; }
        .info-value { font-size: 1.1rem; font-weight: 700; color: #1e3a5f; }
        .info-divider { width: 1px; height: 40px; background: #cbd5e1; }
        .form-section { margin-bottom: 2.5rem; }
        .section-title { font-size: 1.3rem; font-weight: 700; color: #2d3748; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #16a085; display: inline-block; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
        .form-group { display: flex; flex-direction: column; }
        label { font-size: 0.95rem; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; }
        label .req { color: #e53e3e; }
        input, select { padding: 0.875rem 1.125rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; font-family: 'Vazirmatn', sans-serif; transition: all 0.3s; background: white; }
        input:focus, select:focus { outline: none; border-color: #16a085; box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1); }
        input::placeholder { color: #a0aec0; }
        .tractor-card { background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 16px; padding: 2rem; margin-bottom: 1.5rem; }
        .tractor-card:hover { border-color: #16a085; }
        .tractor-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .tractor-number { font-size: 1.2rem; font-weight: 700; color: #16a085; }
        .btn-remove { padding: 0.5rem 1rem; background: #feb2b2; border: none; border-radius: 8px; color: #742a2a; font-weight: 600; cursor: pointer; font-family: 'Vazirmatn', sans-serif; }
        .btn-add { width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; color: white; font-size: 1rem; font-weight: 600; cursor: pointer; font-family: 'Vazirmatn', sans-serif; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .file-box { border: 2px dashed #cbd5e0; border-radius: 12px; padding: 1.5rem; text-align: center; cursor: pointer; background: #f7fafc; }
        .file-box:hover { border-color: #16a085; background: #f0fdf4; }
        .file-box p { color: #4a5568; font-weight: 600; margin-bottom: 0.25rem; }
        .file-box small { color: #a0aec0; }
        .file-info { margin-top: 0.5rem; color: #16a085; font-size: 0.85rem; font-weight: 500; }
        input[type="file"] { display: none; }
        .form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn-back { flex: 1; padding: 1.25rem; background: white; border: 2px solid #e2e8f0; color: #4a5568; font-size: 1.1rem; font-weight: 600; border-radius: 12px; cursor: pointer; font-family: 'Vazirmatn', sans-serif; }
        .btn-submit { flex: 2; padding: 1.25rem; background: linear-gradient(135deg, #16a085 0%, #0f9b6b 100%); border: none; color: white; font-size: 1.1rem; font-weight: 700; border-radius: 12px; cursor: pointer; font-family: 'Vazirmatn', sans-serif; box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3); }
        .btn-submit:hover { transform: translateY(-2px); }
        .server-error { background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .server-error ul { list-style: none; padding: 0; }
        @media (max-width: 768px) { .form-container { padding: 1.5rem; } .form-row { grid-template-columns: 1fr; } .info-box { flex-direction: column; gap: 1rem; } .info-divider { width: 100%; height: 1px; } }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title" id="formTitle">فرم ثبت‌نام</h1>
            <p class="form-subtitle">لطفاً تمام فیلدها را تکمیل نمایید</p>
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
        <div class="server-error">
            <ul>@foreach ($errors->all() as $error)<li>⚠️ {{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        @if (session('error'))
        <div class="server-error">⚠️ {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('client.register.submit') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="province" id="hProvince" value="{{ old('province') }}">
            <input type="hidden" name="organization" id="hOrganization" value="{{ old('organization') }}">

            <div class="form-section">
                <h3 class="section-title">اطلاعات شخصی</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>نام و نام خانوادگی <span class="req">*</span></label>
                        <input type="text" name="full_name" placeholder="علی احمدی" required value="{{ old('full_name') }}">
                    </div>
                    <div class="form-group">
                        <label>شماره تلفن همراه <span class="req">*</span></label>
                        <input type="tel" name="phone" placeholder="09123456789" required value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>کد ملی <span class="req">*</span></label>
                        <input type="text" name="national_id" placeholder="1234567890" required value="{{ old('national_id') }}">
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
                        <input type="text" name="installation_address" placeholder="آدرس کامل محل نصب..." required value="{{ old('installation_address') }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-back" onclick="history.back()">بازگشت</button>
                <button type="submit" class="btn-submit">ثبت اطلاعات</button>
            </div>
        </form>
    </div>
</div>

<script>
var tc = 1;

function fileChosen(input, idx) {
    var info = document.getElementById('fi' + idx);
    if (input.files.length > 0) { info.textContent = '📎 ' + input.files[0].name; }
    else { info.textContent = ''; }
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
</script>
</body>
</html>