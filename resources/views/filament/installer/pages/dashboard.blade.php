<x-filament-panels::page>
    <style>
        /* موبایل فرندلی */
        .installer-dashboard { direction: rtl; font-family: Vazirmatn, sans-serif; padding: 0; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
        
        .stat-card {
            border-radius: 20px; padding: 16px 12px; text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }
        .stat-card .number { font-size: 28px; font-weight: 800; line-height: 1; }
        .stat-card .label { font-size: 11px; font-weight: 600; margin-top: 6px; }
        
        .stat-pending { background: linear-gradient(135deg, #fef3c7, #fde68a); }
        .stat-pending .number { color: #92400e; }
        .stat-pending .label { color: #a16207; }
        
        .stat-done { background: linear-gradient(135deg, #dcfce7, #bbf7d0); }
        .stat-done .number { color: #166534; }
        .stat-done .label { color: #15803d; }
        
        .stat-total { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .stat-total .number { color: #1e40af; }
        .stat-total .label { color: #2563eb; }

        .tab-bar {
            display: flex; gap: 8px; margin-bottom: 16px;
            position: sticky; top: 0; z-index: 10;
            background: white; padding: 12px 0;
        }
        
        .tab-btn {
            flex: 1; padding: 14px 8px; border-radius: 16px;
            font-family: Vazirmatn, sans-serif; font-size: 14px; font-weight: 700;
            cursor: pointer; border: none; text-align: center;
            transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .tab-pending { background: #fef3c7; color: #92400e; }
        .tab-pending.active { background: #f59e0b; color: white; box-shadow: 0 4px 15px rgba(245,158,11,0.4); }
        
        .tab-done { background: #dcfce7; color: #166534; }
        .tab-done.active { background: #22c55e; color: white; box-shadow: 0 4px 15px rgba(34,197,94,0.4); }

        /* کارت مشتری برای موبایل */
        .customer-cards { display: flex; flex-direction: column; gap: 12px; }
        
        .customer-card {
            background: white; border-radius: 20px; padding: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        .customer-card:active { transform: scale(0.98); }
        
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .card-name { font-size: 16px; font-weight: 800; color: #1e293b; }
        .card-status {
            font-size: 11px; font-weight: 600; padding: 4px 12px;
            border-radius: 20px;
        }
        .status-ready { background: #dbeafe; color: #1e40af; }
        .status-waiting { background: #fef3c7; color: #92400e; }
        .status-installed { background: #dcfce7; color: #166534; }
        
        .card-info { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 14px; }
        .info-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #64748b; }
        .info-item .icon { font-size: 16px; }
        .info-item .value { color: #334155; font-weight: 500; }
        
        .card-device {
            background: #f8fafc; border-radius: 12px; padding: 10px 14px;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 14px; border: 1px solid #e2e8f0;
        }
        .device-serial { font-weight: 700; color: #3b82f6; font-size: 14px; }
        .device-sim { font-size: 12px; color: #64748b; direction: ltr; }
        
        .card-address {
            font-size: 12px; color: #64748b; margin-bottom: 14px;
            padding: 8px 12px; background: #fffbeb; border-radius: 10px;
            border-right: 3px solid #f59e0b;
        }
        
        .card-actions { display: flex; gap: 8px; }
        .card-actions button, .card-actions a {
            flex: 1; padding: 12px; border-radius: 14px; border: none;
            font-family: Vazirmatn, sans-serif; font-size: 13px; font-weight: 700;
            cursor: pointer; text-align: center; text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-install { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; box-shadow: 0 4px 15px rgba(34,197,94,0.3); }
        .btn-fail { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; box-shadow: 0 4px 15px rgba(239,68,68,0.3); }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
        .empty-state .emoji { font-size: 48px; margin-bottom: 12px; }
        .empty-state .text { font-size: 15px; font-weight: 600; }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .stat-card { padding: 12px 8px; }
            .stat-card .number { font-size: 24px; }
            .stat-card .label { font-size: 10px; }
            .card-info { grid-template-columns: 1fr; }
            .card-actions { flex-direction: column; }
        }
    </style>

    <div class="installer-dashboard">

        {{-- آمار --}}
        <div class="stats-grid">
            <div class="stat-card stat-pending">
                <div class="number">
                    {{ \App\Models\Registration::where('installer_id', auth()->id())->whereIn('status', ['device_assigned', 'ready_for_installation'])->count() }}
                </div>
                <div class="label">⏳ در انتظار نصب</div>
            </div>
            <div class="stat-card stat-done">
                <div class="number">
                    {{ \App\Models\Registration::where('installer_id', auth()->id())->where('status', 'installed')->count() }}
                </div>
                <div class="label">✅ نصب شده</div>
            </div>
            <div class="stat-card stat-total">
                <div class="number">
                    {{ \App\Models\Registration::where('installer_id', auth()->id())->count() }}
                </div>
                <div class="label">📊 کل</div>
            </div>
        </div>

        {{-- تب‌ها --}}
        <div class="tab-bar">
            <button wire:click="setTab('pending')" class="tab-btn tab-pending {{ $activeTab === 'pending' ? 'active' : '' }}">
                ⏳ در انتظار نصب
            </button>
            <button wire:click="setTab('installed')" class="tab-btn tab-done {{ $activeTab === 'installed' ? 'active' : '' }}">
                ✅ نصب شده‌ها
            </button>
        </div>

        {{-- جدول --}}
        {{ $this->table }}

    </div>
</x-filament-panels::page>