<x-filament-panels::page>
    <style>
        .installer-dash { direction: rtl; font-family: Vazirmatn, sans-serif; }

        /* هدر خوش‌آمدگویی */
        .welcome-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f766e 100%);
            border-radius: 24px; padding: 28px 24px; margin-bottom: 20px;
            display: flex; justify-content: space-between; align-items: center;
            color: white; position: relative; overflow: hidden;
        }
        .welcome-bar::before {
            content: ''; position: absolute; top: -50%; right: -20%;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .welcome-bar::after {
            content: ''; position: absolute; bottom: -60%; left: -10%;
            width: 250px; height: 250px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .welcome-text { position: relative; z-index: 2; }
        .welcome-name { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
        .welcome-sub { font-size: 13px; opacity: 0.7; font-weight: 500; }
        .welcome-date {
            position: relative; z-index: 2;
            background: rgba(255,255,255,0.1); border-radius: 14px;
            padding: 10px 16px; text-align: center; backdrop-filter: blur(10px);
        }
        .welcome-date .day { font-size: 24px; font-weight: 800; line-height: 1; }
        .welcome-date .month { font-size: 11px; opacity: 0.8; margin-top: 2px; }

        /* آمار */
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
        .stat-box {
            border-radius: 20px; padding: 20px 16px; text-align: center;
            position: relative; overflow: hidden; transition: all 0.3s ease;
        }
        .stat-box:hover { transform: translateY(-3px); }
        .stat-box::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 4px; border-radius: 20px 20px 0 0;
        }
        .stat-box .icon { font-size: 28px; margin-bottom: 8px; opacity: 0.9; }
        .stat-box .num { font-size: 32px; font-weight: 900; line-height: 1; margin-bottom: 4px; }
        .stat-box .txt { font-size: 12px; font-weight: 600; }

        .stat-wait { background: linear-gradient(180deg, #fffbeb 0%, #fef3c7 100%); }
        .stat-wait::after { background: #f59e0b; }
        .stat-wait .num { color: #92400e; }
        .stat-wait .txt { color: #a16207; }

        .stat-ok { background: linear-gradient(180deg, #f0fdf4 0%, #dcfce7 100%); }
        .stat-ok::after { background: #22c55e; }
        .stat-ok .num { color: #166534; }
        .stat-ok .txt { color: #15803d; }

        .stat-all { background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%); }
        .stat-all::after { background: #3b82f6; }
        .stat-all .num { color: #1e40af; }
        .stat-all .txt { color: #2563eb; }

        /* درخواست‌های اخیر */
        .requests-section { margin-bottom: 20px; }
        .section-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px;
        }
        .section-title { font-size: 15px; font-weight: 700; color: #1e293b; }
        .request-cards { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 8px; }
        .request-cards::-webkit-scrollbar { height: 4px; }
        .request-cards::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .request-card {
            min-width: 200px; background: white; border-radius: 16px; padding: 14px;
            border: 1px solid #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            flex-shrink: 0; transition: all 0.2s ease;
        }
        .request-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .req-type { font-size: 12px; font-weight: 700; margin-bottom: 6px; }
        .req-type.faulty { color: #ef4444; }
        .req-type.relocation { color: #f59e0b; }
        .req-name { font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 4px; }
        .req-date { font-size: 11px; color: #94a3b8; }
        .req-status {
            display: inline-block; font-size: 10px; font-weight: 700;
            padding: 3px 10px; border-radius: 10px; margin-top: 8px;
        }
        .req-pending { background: #fef3c7; color: #92400e; }
        .req-approved { background: #dcfce7; color: #166534; }
        .req-rejected { background: #fee2e2; color: #991b1b; }

        /* تب‌ها */
        .tabs-wrap {
            display: flex; gap: 8px; margin-bottom: 20px;
            background: #f1f5f9; border-radius: 18px; padding: 6px;
        }
        .tab-item {
            flex: 1; padding: 14px 12px; border-radius: 14px;
            font-family: Vazirmatn, sans-serif; font-size: 14px; font-weight: 700;
            cursor: pointer; border: none; text-align: center;
            transition: all 0.3s ease; background: transparent; color: #64748b;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .tab-item:hover { color: #475569; }
        .tab-item.active {
            background: white; color: #1e293b;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .tab-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 24px; height: 24px; border-radius: 12px;
            font-size: 12px; font-weight: 800; padding: 0 6px;
        }
        .badge-wait { background: #fef3c7; color: #92400e; }
        .badge-ok { background: #dcfce7; color: #166534; }
        .tab-item.active .badge-wait { background: #f59e0b; color: white; }
        .tab-item.active .badge-ok { background: #22c55e; color: white; }

        /* جدول wrapper */
        .table-wrap {
            background: white; border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9; overflow: hidden;
        }

        /* نکته پایین */
        .help-bar {
            margin-top: 16px; background: #f0f9ff; border: 1px solid #bae6fd;
            border-radius: 14px; padding: 12px 16px;
            display: flex; align-items: center; gap: 10px;
            font-size: 12px; color: #0369a1; font-weight: 500;
        }
        .help-bar .help-icon { font-size: 18px; }

        @media (max-width: 640px) {
            .welcome-bar { flex-direction: column; gap: 16px; text-align: center; padding: 20px 16px; }
            .stats-row { gap: 8px; }
            .stat-box { padding: 14px 10px; }
            .stat-box .num { font-size: 26px; }
            .stat-box .icon { font-size: 22px; }
            .stat-box .txt { font-size: 10px; }
            .tab-item { font-size: 12px; padding: 12px 8px; }
            .tab-badge { min-width: 20px; height: 20px; font-size: 10px; }
            .request-card { min-width: 170px; padding: 12px; }
        }
    </style>

    @php
        $pendingCount = \App\Models\Registration::where('installer_id', auth()->id())
            ->whereIn('status', ['device_assigned', 'ready_for_installation'])->count();
        $installedCount = \App\Models\Registration::where('installer_id', auth()->id())
            ->where('status', 'installed')->count();
        $totalCount = \App\Models\Registration::where('installer_id', auth()->id())->count();
        $recentRequests = \App\Models\InstallerRequest::where('installer_id', auth()->id())
            ->latest()->limit(5)->get();
        $jalaliDate = \App\Helpers\JalaliHelper::toJalali(now(), 'j');
        $jalaliMonth = \App\Helpers\JalaliHelper::toJalali(now(), 'F Y');
    @endphp

    <div class="installer-dash">

        {{-- هدر خوش‌آمدگویی --}}
        <div class="welcome-bar">
            <div class="welcome-text">
                <div class="welcome-name">سلام {{ auth()->user()->name }} 👋</div>
                <div class="welcome-sub">خوش آمدید به پنل نصب</div>
            </div>
            <div class="welcome-date">
                <div class="day">{{ $jalaliDate }}</div>
                <div class="month">{{ $jalaliMonth }}</div>
            </div>
        </div>

        {{-- آمار --}}
        <div class="stats-row">
            <div class="stat-box stat-wait">
                <div class="icon">⏳</div>
                <div class="num">{{ $pendingCount }}</div>
                <div class="txt">در انتظار نصب</div>
            </div>
            <div class="stat-box stat-ok">
                <div class="icon">✅</div>
                <div class="num">{{ $installedCount }}</div>
                <div class="txt">نصب شده</div>
            </div>
            <div class="stat-box stat-all">
                <div class="icon">📊</div>
                <div class="num">{{ $totalCount }}</div>
                <div class="txt">کل</div>
            </div>
        </div>

        {{-- درخواست‌های اخیر --}}
        @if($recentRequests->isNotEmpty())
        <div class="requests-section">
            <div class="section-header">
                <div class="section-title">📋 درخواست‌های اخیر شما</div>
            </div>
            <div class="request-cards">
                @foreach($recentRequests as $req)
                <div class="request-card">
                    <div class="req-type {{ $req->type }}">
                        {{ $req->type === 'faulty' ? '⚠️ معیوبی' : '🔄 جابجایی' }}
                    </div>
                    <div class="req-name">{{ $req->registration?->full_name ?? '—' }}</div>
                    <div class="req-date">{{ \App\Helpers\JalaliHelper::toJalali($req->created_at, 'Y/m/d') }}</div>
                    <span class="req-status req-{{ $req->status }}">
                        {{ match($req->status) { 'pending' => '⏳ در انتظار', 'approved' => '✅ تأیید شده', 'rejected' => '❌ رد شده', default => $req->status } }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- تب‌ها --}}
        <div class="tabs-wrap">
            <button wire:click="setTab('pending')" class="tab-item {{ $activeTab === 'pending' ? 'active' : '' }}">
                ⏳ در انتظار نصب
                <span class="tab-badge badge-wait">{{ $pendingCount }}</span>
            </button>
            <button wire:click="setTab('installed')" class="tab-item {{ $activeTab === 'installed' ? 'active' : '' }}">
                ✅ نصب شده‌ها
                <span class="tab-badge badge-ok">{{ $installedCount }}</span>
            </button>
        </div>

        {{-- جدول --}}
        <div class="table-wrap">
            {{ $this->table }}
        </div>

        {{-- نکته کمکی --}}
        <div class="help-bar">
            <span class="help-icon">💡</span>
            <span>برای ثبت گزارش نصب یا عدم نصب، روی دکمه مربوطه در هر ردیف کلیک کنید.</span>
        </div>

    </div>
</x-filament-panels::page>