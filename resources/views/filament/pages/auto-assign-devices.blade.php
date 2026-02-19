<x-filament-panels::page>
    <div style="direction: rtl; font-family: Vazirmatn, sans-serif;">

        <form wire:submit.prevent="calculateStats">
            {{ $this->form }}

            <div style="margin-top: 20px; display: flex; gap: 12px;">
                <button type="submit"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: Vazirmatn, sans-serif; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                    🔍 محاسبه آمار
                </button>
            </div>
        </form>

        @if($showStats)
        <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #86efac; border-radius: 16px; padding: 24px; text-align: center;">
                <div style="font-size: 40px; font-weight: 800; color: #166534;">{{ $availableDevices }}</div>
                <div style="font-size: 14px; color: #15803d; font-weight: 600; margin-top: 8px;">📦 دستگاه موجود (سیمکارت‌دار)</div>
            </div>
            <div style="background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border: 2px solid #fde047; border-radius: 16px; padding: 24px; text-align: center;">
                <div style="font-size: 40px; font-weight: 800; color: #854d0e;">{{ $waitingCustomers }}</div>
                <div style="font-size: 14px; color: #a16207; font-weight: 600; margin-top: 8px;">👥 متقاضی در انتظار دستگاه (تایید مالی شده)</div>
            </div>
        </div>

        <div style="margin-top: 24px; background: white; border: 2px solid #e2e8f0; border-radius: 16px; padding: 24px;">
            <label style="font-size: 15px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 12px;">
                تعداد اختصاص:
            </label>
            <div style="display: flex; gap: 12px; align-items: center;">
                <input type="number" wire:model="assign_count" min="1" max="{{ min($availableDevices, $waitingCustomers) }}"
                    placeholder="مثلاً ۵۰"
                    style="padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; font-family: Vazirmatn, sans-serif; width: 200px; text-align: center;">
                <span style="color: #64748b; font-size: 13px;">حداکثر {{ min($availableDevices, $waitingCustomers) }} عدد</span>
            </div>

            <button wire:click="executeAssign" wire:loading.attr="disabled"
                style="margin-top: 20px; background: linear-gradient(135deg, #16a085 0%, #0f9b6b 100%); color: white; padding: 14px 40px; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; font-family: Vazirmatn, sans-serif; box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3); display: flex; align-items: center; gap: 8px;">
                <span wire:loading.remove wire:target="executeAssign">⚡ اجرای اختصاص خودکار</span>
                <span wire:loading wire:target="executeAssign">⏳ در حال اجرا...</span>
            </button>
        </div>
        @endif

        @if($showResult)
        <div style="margin-top: 24px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #4ade80; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 48px;">✅</div>
            <div style="font-size: 22px; font-weight: 800; color: #166534; margin-top: 12px;">{{ $assignedCount }} دستگاه با موفقیت اختصاص داده شد</div>
            <div style="font-size: 14px; color: #15803d; margin-top: 8px;">وضعیت متقاضیان به «منتظر آماده‌سازی» تغییر کرد</div>
        </div>
        @endif
        @if(count($assignedList) > 0)
        <div style="margin-top: 24px; background: white; border: 2px solid #e2e8f0; border-radius: 16px; padding: 24px; overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1e293b;">📋 لیست اختصاص داده شده‌ها</h3>
                <span style="background: #dbeafe; color: #1e40af; padding: 4px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">{{ count($assignedList) }} مورد</span>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 700; color: #475569;">#</th>
                        <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 700; color: #475569;">نام متقاضی</th>
                        <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 700; color: #475569;">تلفن</th>
                        <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 700; color: #475569;">شهرستان</th>
                        <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 700; color: #475569;">سریال دستگاه</th>
                        <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 700; color: #475569;">شماره سیمکارت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedList as $index => $item)
                    <tr style="{{ $index % 2 === 0 ? 'background: white;' : 'background: #f9fafb;' }}">
                        <td style="padding: 10px; border: 1px solid #e2e8f0; text-align: center; font-weight: 600; color: #64748b;">{{ $index + 1 }}</td>
                        <td style="padding: 10px; border: 1px solid #e2e8f0; font-weight: 600;">{{ $item['full_name'] }}</td>
                        <td style="padding: 10px; border: 1px solid #e2e8f0; direction: ltr; text-align: right;">{{ $item['phone'] }}</td>
                        <td style="padding: 10px; border: 1px solid #e2e8f0;">{{ $item['city'] }}</td>
                        <td style="padding: 10px; border: 1px solid #e2e8f0;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 2px 10px; border-radius: 8px; font-size: 12px; font-weight: 600;">{{ $item['serial_number'] }}</span>
                        </td>
                        <td style="padding: 10px; border: 1px solid #e2e8f0; direction: ltr; text-align: right;">{{ $item['sim_number'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</x-filament-panels::page>