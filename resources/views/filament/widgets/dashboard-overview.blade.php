<x-filament-widgets::widget>
    <style>
        .dash { direction: rtl; font-family: Vazirmatn, sans-serif; }
        .dash-grid { display: grid; gap: 16px; margin-bottom: 24px; }
        .dash-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .dash-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .dash-grid-2 { grid-template-columns: repeat(2, 1fr); }

        .dash-card {
            background: white; border-radius: 16px; padding: 20px;
            border: 1px solid #f1f5f9; position: relative; overflow: hidden;
            transition: all 0.2s ease;
        }
        .dash-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.06); transform: translateY(-2px); }
        .dash-card .number { font-size: 28px; font-weight: 800; margin-bottom: 4px; }
        .dash-card .label { font-size: 13px; font-weight: 600; color: #64748b; }
        .dash-card .icon { position: absolute; left: 16px; top: 16px; font-size: 20px; opacity: 0.15; }
        .dash-card .accent { position: absolute; bottom: 0; right: 0; left: 0; height: 3px; }

        .dash-section { margin-bottom: 24px; }
        .dash-section-title { font-size: 15px; font-weight: 700; color: #475569; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }

        .progress-ring { width: 120px; height: 120px; margin: 0 auto; }
        .progress-card { text-align: center; background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; }
        .progress-card .percent { font-size: 32px; font-weight: 800; color: #059669; }
        .progress-card .ptext { font-size: 12px; color: #64748b; font-weight: 600; margin-top: 4px; }

        .recent-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .recent-table th { padding: 10px 12px; text-align: right; color: #64748b; font-weight: 600; font-size: 12px; border-bottom: 2px solid #f1f5f9; }
        .recent-table td { padding: 10px 12px; border-bottom: 1px solid #f8fafc; }
        .recent-table tr:hover td { background: #f8fafc; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }

        @media (max-width: 768px) {
            .dash-grid-4 { grid-template-columns: repeat(2, 1fr); }
            .dash-grid-3 { grid-template-columns: repeat(1, 1fr); }
        }
    </style>

    <div class="dash">

        {{-- بخش اصلی: فرایند --}}
        <div class="dash-section">
            <div class="dash-section-title">📊 وضعیت فرایند</div>
            <div class="dash-grid dash-grid-4">
                <div class="dash-card">
                    <div class="icon">⏳</div>
                    <div class="number" style="color: #f59e0b;">{{ $stats['pending'] }}</div>
                    <div class="label">در انتظار تأیید مالی</div>
                    <div class="accent" style="background: #f59e0b;"></div>
                </div>
                <div class="dash-card">
                    <div class="icon">💰</div>
                    <div class="number" style="color: #22c55e;">{{ $stats['financial_approved'] }}</div>
                    <div class="label">منتظر اختصاص دستگاه</div>
                    <div class="accent" style="background: #22c55e;"></div>
                </div>
                <div class="dash-card">
                    <div class="icon">🔍</div>
                    <div class="number" style="color: #3b82f6;">{{ $stats['device_assigned'] }}</div>
                    <div class="label">منتظر آماده‌سازی</div>
                    <div class="accent" style="background: #3b82f6;"></div>
                </div>
                <div class="dash-card">
                    <div class="icon">🔧</div>
                    <div class="number" style="color: #06b6d4;">{{ $stats['ready_for_installation'] }}</div>
                    <div class="label">آماده نصب</div>
                    <div class="accent" style="background: #06b6d4;"></div>
                </div>
            </div>
        </div>

        {{-- بخش خلاصه --}}
        <div class="dash-section">
            <div class="dash-grid dash-grid-3">
                <div class="progress-card">
                    <div class="percent">{{ $stats['install_rate'] }}%</div>
                    <div class="ptext">نرخ نصب</div>
                    <div style="margin-top: 8px; font-size: 12px; color: #94a3b8;">{{ $stats['installed'] }} از {{ $stats['total_customers'] }} متقاضی</div>
                </div>
                <div class="dash-card" style="display: flex; flex-direction: column; justify-content: center;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span class="label">📦 کل دستگاه‌ها</span>
                        <span style="font-weight: 700; color: #1e293b;">{{ $stats['total_devices'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span class="label">✅ موجود</span>
                        <span style="font-weight: 700; color: #22c55e;">{{ $stats['available_devices'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="label">⚠️ معیوب</span>
                        <span style="font-weight: 700; color: #ef4444;">{{ $stats['faulty_devices'] }}</span>
                    </div>
                </div>
                <div class="dash-card" style="display: flex; flex-direction: column; justify-content: center;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span class="label">✅ نصب شده</span>
                        <span style="font-weight: 700; color: #059669;">{{ $stats['installed'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span class="label">🔧 نصاب فعال</span>
                        <span style="font-weight: 700; color: #3b82f6;">{{ $stats['active_installers'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="label">🔄 درخواست جابجایی</span>
                        <span style="font-weight: 700; color: #f59e0b;">{{ $stats['relocation_requested'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- آخرین ثبت‌نام‌ها --}}
        <div class="dash-section">
            <div class="dash-section-title">📝 آخرین ثبت‌نام‌ها</div>
            <div class="dash-card" style="padding: 0; overflow: hidden;">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>نام</th>
                            <th>تلفن</th>
                            <th>سازمان</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $reg)
                        <tr>
                            <td style="font-weight: 600; color: #1e293b;">{{ $reg->full_name }}</td>
                            <td style="direction: ltr; text-align: right;">{{ $reg->phone }}</td>
                            <td>
                                @php
                                    $orgLabel = match($reg->organization) { 'jihad' => 'جهاد کشاورزی', 'sanat' => 'صنعت معدن و تجارت', 'shilat' => 'سازمان شیلات', default => $reg->organization ?? '—' };
                                    $orgColor = match($reg->organization) { 'jihad' => '#dcfce7;color:#166534', 'sanat' => '#fee2e2;color:#991b1b', 'shilat' => '#dbeafe;color:#1e40af', default => '#f1f5f9;color:#475569' };
                                @endphp
                                <span class="badge" style="background:{{ $orgColor }}">{{ $orgLabel }}</span>
                            </td>
                            <td>
                                @php
                                    $statusLabel = match($reg->status) { 'pending' => 'انتظار تأیید', 'financial_approved' => 'تأیید مالی', 'device_assigned' => 'منتظر آماده‌سازی', 'ready_for_installation' => 'آماده نصب', 'installed' => 'نصب شده', 'relocation_requested' => 'جابجایی', default => $reg->status };
                                    $statusColor = match($reg->status) { 'pending' => '#fef3c7;color:#92400e', 'financial_approved' => '#dcfce7;color:#166534', 'device_assigned' => '#dbeafe;color:#1e40af', 'ready_for_installation' => '#cffafe;color:#155e75', 'installed' => '#d1fae5;color:#065f46', 'relocation_requested' => '#fee2e2;color:#991b1b', default => '#f1f5f9;color:#475569' };
                                @endphp
                                <span class="badge" style="background:{{ $statusColor }}">{{ $statusLabel }}</span>
                            </td>
                            <td style="color: #94a3b8; font-size: 12px;">{{ \App\Helpers\JalaliHelper::toJalaliDateTime($reg->created_at) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>