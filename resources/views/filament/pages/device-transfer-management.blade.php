<x-filament-panels::page>
    <div style="direction: rtl; font-family: Vazirmatn, sans-serif;">

        <form wire:submit.prevent="executeTransfer">
            {{ $this->form }}

            @if($transfer_type)
            <div style="margin-top: 20px;">
                <button type="submit"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 14px 40px; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; font-family: Vazirmatn, sans-serif; box-shadow: 0 4px 15px rgba(245,158,11,0.3); display: flex; align-items: center; gap: 8px;">
                    🔄 اجرای جابجایی
                </button>
            </div>
            @endif
        </form>

        @if($showResult)
        <div style="margin-top: 24px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #4ade80; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 48px;">✅</div>
            <div style="font-size: 16px; font-weight: 700; color: #166534; margin-top: 12px; line-height: 1.8;">{{ $resultMessage }}</div>
        </div>
        @endif

    </div>
</x-filament-panels::page>